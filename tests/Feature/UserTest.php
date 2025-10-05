<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\UserService;

class UserTest extends TestCase
{
    use DatabaseTransactions;
    
    /**
     * @expectedException           Exception
     * @expectedExceptionMessage    101
     */
    public function test_add_error()
    {
        $service = new UserService();
        $result = $service->add(['name' => 'admin', 'email' => 'baruch@metatron.co.il', 'password' => '123123', 'phone' => '051-02020200', 'is_verified' => 1]);
    }

    public function test_add_success()
    {
        $service = new UserService();
        $result = $service->add(['name' => 'admin', 'email' => 'admin20@metatron.co.il', 'password' => '123123', 'phone' => '051-02020200','is_verified' => 1]);
        $this->assertEquals('admin', $result->name);

        $token = $service->login(['email' => 'admin20@metatron.co.il', 'password' => '123123']);
    }

    /**
     * @expectedException           Exception
     * @expectedExceptionMessage    We cant find an account with this credentials.
     */
    public function test_login_failed()
    {
        $service = new UserService();
        $service->login(['email' => 'admin20@metatron.co.il', 'password' => '123123']);
    }

    /**
     * @expectedException           Exception
     * @expectedExceptionMessage    We can't find a user with that e-mail address.
     */
    public function test_recover_emailFailed()
    {
        $service = new UserService();
        $result = $service->recover('baruch@metatron.co.il2');
        $this->assertEquals(true, $result);
    }
    
    public function test_recover_success()
    {
        $service = new UserService();
        $result = $service->recover('baruch@metatron.co.il');
        $this->assertEquals(true, $result);
    }

    /**
     * @expectedException           Exception
     * @expectedExceptionMessage    This password reset token is invalid.
     */
    public function test_reset_emailFailed()
    {
        $service = new UserService();
        $result = $service->reset('1234', 'baruch@metatron.co.il', '123321');
        $this->assertEquals(true, $result);
    }

    public function test_reset_success()
    {
        $service = new UserService();
        $result = $service->reset('123', 'baruch@metatron.co.il', '123321');
        $this->assertEquals(true, $result);
    }

    public function test_getTotal()
    {
       $service = new UserService();
       $result = $service->getTotal();
       $this->assertEquals(2, $result);
    }

    public function test_getTotal_filtered()
    {
        $service = new UserService();
        $result = $service->getTotal("01/01/2000", "01/01/2030");
        $this->assertEquals(2, $result);
    }
    
    public function test_getAllPaginate()
    {
        $service = new UserService();
        $data = [
            'type' => ['U'],
            'name' => '%',
            'email' => '%',
            'sort' => 'id',
            'order' => 'desc'
        ];
        $result = $service->getAllPaginate($data);
        $this->assertEquals(1, count($result));
    }

    public function test_getAll()
    {
        $service = new UserService();
        $result = $service->getAll();
        $this->assertEquals(2, count($result));
    }

    /**
     * @expectedException           Exception
     * @expectedExceptionMessage    Error, id not fou
     */
    public function test_getById_error()
    {
        $service = new UserService();
        $result = $service->getById(3000);
    }

    public function test_getById_success()
    {
        $service = new UserService();
        $result = $service->getById(2);
        $this->assertEquals(2, $result->id);
    }

    public function test_update_success()
    {
        $service = new UserService();
        $result = $service->update(2, ['email', 'rda@re.re']);
        $this->assertEquals(true, $result);
    }

    public function test_delete_success()
    {
        $service = new UserService();
        $result = $service->delete(2);
        $this->assertEquals(true, $result);
    }

    public function test_getWinners()
    {
        $service = new UserService();
        $data = [
            'minimum' => 1
        ];
        $result = $service->getWinners($data);
        $this->assertEquals(1, count($result));
    }

    public function test_getFinalistWinners()
    {
        $service = new UserService();
        $data = [
            'minimum' => 0
        ];
        $result = $service->getFinalistWinners($data);
        $this->assertEquals(1, count($result));
    }
}
