<?php
namespace Controller;

use View\View;

class Auth extends BaseController
{
    public function loginAction(array $args)
    {
        return new View('auth/login.html');
    }
}