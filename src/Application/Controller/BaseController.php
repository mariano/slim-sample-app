<?php
namespace Application\Controller;

use Application\View\RendererInterface;
use Application\View\ViewInterface;
use BadMethodCallException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

abstract class BaseController implements ControllerInterface
{
    /**
     * Settings
     *
     * @var array
     */
    protected $settings;

    /**
     * Action request
     *
     * @var RequestInterface
     */
    protected $request;

    /**
     * Renderer
     *
     * @var RendererInterface
     */
    private $renderer;

    /**
     * Action response
     *
     * @var ResponseInterface
     */
    private $response;

    /**
     * Set view renderer
     *
     * @param RendererInterface $renderer Renderer
     */
    public function setRenderer(RendererInterface $renderer)
    {
        $this->renderer = $renderer;
    }

    /**
     * Set settings
     *
     * @param array $settings Settings
     */
    public function setSettings(array $settings)
    {
        $this->settings = $settings;
    }

    /**
     * Wrapper to handle action calls
     *
     * @param string $name Method name
     * @param array $arguments Method arguments
     * @return ResponseInterface Whatever the underlying calls returns
     * @throws BadMethodCallException
     */
    public function __call($name, array $arguments)
    {
        if (
            count($arguments) >= 2 &&
            ($arguments[0] instanceof RequestInterface) &&
            ($arguments[1] instanceof ResponseInterface)
        ) {
            $method = $name.'Action';
            if (!method_exists($this, $method)) {
                throw new BadMethodCallException("Action method {$method} is not defined");
            }

            $this->request = $arguments[0];
            $this->response = $arguments[1];

            $result = $this->{$method}(...array_slice($arguments, 2));
            $response = ($result instanceof ResponseInterface ? $result : $this->response);

            if (is_string($result)) {
                $response->getBody()->write($result);
            } elseif ($result instanceof ViewInterface) {
                $vars = $result->getVars() + [
                    'debug' => !empty($this->settings['view']['debug'])
                ];
                $response->getBody()->write($this->renderer->render($result->getTemplate(), $vars));
            }

            return $response;
        }

        throw new BadMethodCallException();
    }

    /**
     * Redirect
     *
     * This method prepares the response object to return an HTTP Redirect response
     * to the client.
     *
     * @param  string $url    The redirect destination
     * @param  int    $status The redirect HTTP status code
     * @return ResponseInterface Response
     */
    public function redirect($url, $status = 307)
    {
        return $this->response->withStatus($status)
            ->withHeader('Location', $url);
    }
}