<?php
namespace Controller;

use Hybrid_Auth;
use Hybrid_Endpoint;
use Controller\Exception\InvalidUriException;
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

    /**
     * HybridAuth instance
     *
     * @var Hybrid_Auth
     */
    protected $hybridAuth;

    /**
     * Create
     *
     * @param UserStoreInterface $store User store
     * @param Hybrid_Auth $hybridAuth HybridAuth for social logins
     */
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

    public function loginSocialAction(array $args)
    {
        $enabledProviders = array_keys($this->hybridAuth->getProviders());
        $providers = array_combine(array_map('strtolower', $enabledProviders), $enabledProviders);
        if (empty($args['provider']) || !isset($providers[$args['provider']])) {
            throw new InvalidUriException($this->request->getUri());
        }

        $redirectUrl = $this->getLoggedInURL();
        $provider = $providers[$args['provider']];

        if ($this->hybridAuth->isConnectedWith($provider)) {
            $this->redirect($redirectUrl, 307);
            return;
        }

        $this->hybridAuth->authenticate($providers[$args['provider']], [
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

    protected function getLoggedInURL()
    {
        return '/hello/world';
    }
}