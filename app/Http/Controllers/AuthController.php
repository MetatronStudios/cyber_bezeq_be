<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;

/**
 * @codeCoverageIgnore
 */
class AuthController extends ParentController
{
    public function __construct()
    {
        $this->service = new UserService();
    }

    private function reCapchaTest($token, $ip)
    {
        if (config('app.env') == 'local') {
            return true;
        }
        $secretKey = config('app.recaptcha_secret');
        $client = new Client();

        try {
            $response = $client->post('https://www.google.com/recaptcha/api/siteverify', [
                'form_params' => [
                    'secret' => $secretKey,
                    'response' => $token,
                    'remoteip' => $ip
                ]
            ]);

            $body = json_decode($response->getBody(), true);

            if (!$body['success']) {
                return false;
            }
        } catch (\Exception $e) {
            Log::error('Failed to verify reCAPTCHA. error: ' . $e->getMessage());
            return false;
        }

        return true;
    }

    public function register(Request $request)
    {
        // $credentials = $request->only('token', 'name', 'email', 'district', 'password', 'password_confirmation');
        // $validator = Validator::make($credentials, [
        //     'email' => 'bail|required|email|max:255|unique:users',
        //     'name' => 'bail|required|string|max:255',
        //     'district' => 'bail|required|string|max:255',
        //     'password' => 'bail|required|confirmed|min:8|max:16',
        // ]);
        $credentials = $request->only('token', 'name', 'workerNumber');
        $sanitizedName = str_replace([' ', "'", '"'], ['', '', ''], strtolower($credentials['name']));
        $credentials['email'] = $sanitizedName . $credentials['workerNumber'] . '@someelbitnonexists.com';
        $credentials['password'] = 'default_password';
        $validator = Validator::make($credentials, [
            'name' => 'bail|required|string|max:255',
            'workerNumber' => 'bail|required|integer|digits:3',
            'token' => 'required|string',
            'email' => 'email|max:255|unique:users,email',
        ],
            [
                'workerNumber.required' => 'מספר עובד הינו שדה חובה',
                'workerNumber.integer' => 'מספר עובד חייב להיות מספר שלם',
                'workerNumber.digits' => 'מספר עובד חייב להיות באורך 3 ספרות',
                'email.unique' => 'משתמש כבר רשום במערכת',
                'email.email' => 'הראה שגיאה עם זיהוי משתמש',
                'email.max' => 'הראה שגיאה עם זיהוי משתמש',
            ]
        );


        if ($validator->fails()) {
            $errorLog = $request->only('name', 'workerNumber');
            Log::warning('register failed req:' . json_encode($errorLog) . ' errors: ' . json_encode($validator->errors()));
            $error = $validator->errors();
            return $this->respondWithError(array_reverse($error->toArray()));
        }

        if (!$this->reCapchaTest($credentials['token'], $request->ip())) {
            $error[0] = 'תקלה בבדיקת בוטים, נסו שוב בעוד מספר דקות.';
            return $this->respondWithError(['error' => $error], 422);
        }

        try {
            $credentials['ip_address'] = $request->ip();
            $credentials['is_verified'] = 1; // remove this line when email verification is implemented
            $user = $this->service->add($credentials);
            $credentials = $user->only('email');
            $credentials['password'] = 'default_password'; // default password for login
            $token = $this->service->login($credentials);
            return $this->respondWithData(['token' => $token]);
        } catch (\Exception $e) {
            $errorLog = $request->only('name', 'workerNumber');
            Log::warning('register2 failed req:' . json_encode($errorLog) . ' errors: ' . json_encode($e->getMessage()));
            return $this->respondWithError($e->getMessage());
        }
    }

    public function login(Request $request)
    {
        $credentials = $request->only('token', 'workerNumber', 'name', 'email', 'password');
        if (isset($credentials['workerNumber'])) {
            $sanitizedName = str_replace([' ', "'", '"'], ['', '', ''], strtolower($request->input('name')));
            $credentials['email'] = $sanitizedName . $credentials['workerNumber'] . '@someelbitnonexists.com';
            $credentials['password'] = 'default_password';
            $validator = Validator::make($credentials, [
                'workerNumber' => 'bail|required|integer|digits:3',
                'name' => 'bail|required|string|max:255',
                'token' => 'required|string',
                'email' => 'email|max:255',
            ],
                [
                    'workerNumber.required' => 'מספר עובד הינו שדה חובה',
                    'workerNumber.integer' => 'מספר עובד חייב להיות מספר שלם',
                    'workerNumber.digits' => 'מספר עובד חייב להיות באורך 3 ספרות',
                    'email.email' => 'הראה שגיאה עם זיהוי משתמש',
                    'email.max' => 'הראה שגיאה עם זיהוי משתמש',
                ]
            );
        } else {
            // admin logins with email and password
            $credentials['email'] = strtolower($request->input('email'));
            $credentials['password'] = $request->input('password');
            $validator = Validator::make($credentials, [
                'email' => 'bail|required|string|email',
                'password' => 'bail|required|string',
                'token' => 'required|string',
            ]);
        }

        // $validator = Validator::make($request->only('email', 'password'), [
        //     'email' => 'required|string|email',
        //     'password' => 'required',
        // ]);
        if ($validator->fails()) {
            Log::warning('login1 failed req:' . json_encode($credentials['email']) . ' errors: ' . json_encode($validator->errors()));
            return $this->respondWithError($validator->errors());
        }

        if (!$this->reCapchaTest($request->input('token') , $request->ip())) {
            Log::warning('login2 failed req:' . json_encode($credentials['email']. ' errors: תקלה בבדיקת בוטים, נסו שוב בעוד מספר דקות.'));
            $error[0] = 'תקלה בבדיקת בוטים, נסו שוב בעוד מספר דקות.';
            return $this->respondWithError(['error' => $error], 422);
        }

        // $credentials = $request->only('email', 'password');
        $loginCredentials = [
            'email' => $credentials['email'],
            'password' => $credentials['password'],
        ];
        try {
            $token = $this->service->login($loginCredentials);
        } catch (\Exception $e) {
            Log::warning('login3 failed req:' . json_encode($loginCredentials['email']) . ' errors: ' . json_encode($e->getMessage()));
            return $this->respondWithError($e->getMessage(), 404);
        }
        $userId = Auth::id();
        $userInfo = $this->service->getById($userId);
        if (ConfigController::is_final() && ($userInfo->type == 'U')) {
            return $this->respondWithError('לא ניתן להיכנס, אינכם מופיעים ברשימת מקצה הגמר', 406);
        }
        $message = 'כל חלק של פאזל יוביל אתכם לחידה. לחצו על החלק הדרוש והחלו לפתור! בהצלחה!';
        if (ConfigController::is_final())
            $message = "הנכם מחוברים למערכת, בהצלחה במקצה הגמר.";
        return $this->respondWithData(['token' => $token, 'message' => $message]);
    }

    public function recover(Request $request)
    {
        return;
        if (!$this->reCapchaTest($request->input('token') , $request->ip())) {
            $error[0] = 'תקלה בבדיקת בוטים, נסו שוב בעוד מספר דקות.';
            return $this->respondWithError(['error' => $error], 422);
        }
        $request->validate([
            'email' => 'required|string|email',
        ]);
        $this->service->recover($request->email);
        return $this->respondWithData(true);
    }

    public function reset(Request $request)
    {
        return; // This method is not implemented in the provided code snippet.
        $credentials = $request->only('password', 'password_confirmation', 'token');
        $validator = Validator::make($credentials, [
            'password' => 'bail|required|confirmed|min:8|max:16',
            'token' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->respondWithError($validator->errors());
        }

        $this->service->reset($request->token, $request->password);
        return $this->respondWithData(true);
    }

    public function refresh()
    {
        return;
    }

    public function userInfo()
    {
        $userId = Auth::id();
        $userInfo = $this->service->getById($userId);

        if (ConfigController::is_final() && ($userInfo->type == 'U')) {
            return $this->respondWithError('אינכם מופיעים ברשימת מקצה הגמר', 406);
        }
        return $this->respondWithData($userInfo);
    }

}
