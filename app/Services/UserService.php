<?php
namespace App\Services;

// use JWTAuth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Repositories\UserRepository;
use App\Repositories\PasswordResetRepository;
use App\Utils\MailUtil;
use App\Exceptions\BusinessException;
use Illuminate\Support\Facades\Log;

class UserService extends ParentService
{
    public function __construct()
    {
        $this->repository = new UserRepository();
    }

    private function checkEmailExist($email)
    {
        $item = $this->repository->getBy([['email' => $email]]);
        return count($item) == 0 ? false : true;
    }

    public function add($data)
    {
        if ( $this->checkEmailExist($data['email']) ) {
            throw new BusinessException('101');
        }
        $user = parent::add($data);
        if ( $user ) {
            $url = config('app.base_url', '');
            $subject = "Registration confirmation - ";
            $subject .= config('app.project_subject_he', '');
            // MailUtil::sendMail('emails.welcome', ['name'=>$data['name'],'url'=>$url], $data['email'], $subject);
        }
        return $user;
    }

    public function login($data)
    {
        // add is_verified to the data array
        // $data['is_verified'] = 1;

        try {
            $token = JWTAuth::attempt($data);
            if ( !$token ) {
                throw new BusinessException('We cant find an account with this credentials.');
            }
            return $token;
        } catch (JWTException $e) { //@codeCoverageIgnore
            throw new BusinessException('Failed to login, please try again.'); //@codeCoverageIgnore
        }
    }

    private function getByEmail($email)
    {
        $user = $this->repository->getByEmail($email);
        if ( !$user ) {
            Log::info('We can\'t find a user with that e-mail address. userservice:62 '.$email);
            throw new BusinessException('We can\'t find a user with that e-mail address. '.$email);
        }
        return $user;
    }

    public function recover($email)
    {
        $user = $this->getByEmail($email);
        $passResRep = new PasswordResetRepository();
        $passwordReset = $passResRep->updateOrCreate($user->email);
        if ( $passwordReset ) {
            $url = config('app.client_url', '');
            $url .= $passwordReset->token;
            $subject = "Reset password - ";
            $subject .= config('app.project_subject_he', '');
            MailUtil::sendMail('emails.recover_password', ['url' => $url], $user->email,$subject);
        }
        return true;
    }

    public function reset($token, $password)
    {
        $passResRep = new PasswordResetRepository();
        $passwordReset = $passResRep->getByEmailAndToken($token);

        if ( !$passwordReset ) {
            throw new BusinessException('This password reset token is invalid.');
        }

        $user = $this->getByEmail($passwordReset->email);
        $this->repository->update($user, ['password' => $password]);
        $passwordReset->delete();

        //MailUtil::sendMail('emails.password_reset', [], $user->email);
        return true;
    }

    public function getWinners($data)
    {
        return $this->repository->getWinners($data);
    }

    public function getWinnersMembers($data)
    {
        return $this->repository->getWinnersMembers($data);
    }

    public function getWinnersExport()
    {
        return $this->repository->getWinnersExport();
    }


    public function getFinalistWinners($data)
    {
        return $this->repository->getFinalistWinners($data);
    }

    public function getFinalistWinnersExport()
    {
        return $this->repository->getFinalistWinnersExport();
    }

    public function getById($id)
    {
        return $this->repository->getById($id);
    }
}
