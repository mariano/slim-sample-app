<?php
namespace Controller;

use Hybrid_Auth;
use Hybrid_Endpoint;
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

    protected $hybridAuth;

    public function __construct(UserStoreInterface $store, Hybrid_Auth $hybridAuth)
    {
        $this->store = $store;
        $this->hybridAuth = $hybridAuth;
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

    public function loginSocialAction()
    {
        $redirectUrl = '/hello/world';
        $this->hybridAuth->authenticate('Facebook', [
            'hauth_return_to' => $redirectUrl
        ]);
    }

    public function endpointAction()
    {
        Hybrid_Endpoint::process();
    }

    public function logoutAction()
    {
        $this->hybridAuth->logoutAllProviders();
    }
}