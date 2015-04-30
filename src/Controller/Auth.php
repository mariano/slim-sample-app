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

    /**
     * Perform a social login
     *
     * @return Psr\Http\Message\ResponseInterface|null A redirect response, or null
     */
    public function loginSocialAction(array $args)
    {
        $enabledProviders = array_keys($this->hybridAuth->getProviders());
        $providers = array_combine(array_map('strtolower', $enabledProviders), $enabledProviders);
        if (empty($args['provider']) || !isset($providers[$args['provider']])) {
            throw new InvalidUriException($this->request->getUri());
        }

        $adapter = $this->hybridAuth->authenticate($providers[$args['provider']]);
        $profile = $adapter->getUserProfile();
        if (empty($profile)) {
            echo 'EMPTY!'; exit;
            return $this->redirect($this->getLoginURL());
        }

        echo 'SOCIAL PROFILE:';
        var_dump($profile);
        $user = $this->store->getBySocialAccount($args['provider'], $profile);
        echo 'USER:';
        var_dump($user);
        exit;

        return $this->redirect($this->getLoggedInURL());
    }

    /**
     * Process social login callbacks
     */
    public function endpointAction()
    {
        Hybrid_Endpoint::process();
    }

    /**
     * Logout user (including from social networks)
     */
    public function logoutAction()
    {
        $this->hybridAuth->logoutAllProviders();
    }

    /**
     * Get URL user should be redirected to when they need to log in
     *
     * @return string
     */
    protected function getLoginURL()
    {
        return '/login';
    }

    /**
     * Get URL user should be redirected to when logged in
     *
     * @return string
     */
    protected function getLoggedInURL()
    {
        return '/hello/world';
    }
}