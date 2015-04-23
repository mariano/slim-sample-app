<?php
namespace Controller;

use Data\Store\UserStoreInterface;
use View\View;

class Auth extends BaseController
{
    /**
     * User store
     *
     * @var Data\Store\UserStoreInterface
     */
    protected $store;

    public function __construct(UserStoreInterface $store)
    {
        $this->store = $store;
    }

    public function loginAction(array $args)
    {
        return new View('auth/login.html');
    }

    public function doLoginAction()
    {
        var_dump($this->request->getParsedBody());
        var_dump($this->store->getByLogin('email', 'password'));
        exit;
    }
}