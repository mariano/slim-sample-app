<?php
namespace Controller;

use BadMethodCallException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use View\RendererInterface;
use View\ViewInterface;

abstract class BaseController implements ControllerInterface
{
    /**
     * Settings
     *
     * @var array
     */
    protected $settings;

    /**
     * Renderer
     *
     * @var View\RendererInterface
     */
    private $renderer;

    /**
     * Action request
     *
     * @var Psr\Http\Message\RequestInterface
     */
    private $request;

    /**
     * Action response
     *
     * @var Psr\Http\Message\ResponseInterface
     */
    private $response;

    /**
     * Set view renderer
     *
     * @param View\RendererInterface $renderer Renderer
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
     * @return mixed Whatever the underlying calls returns
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
                $response->write($result);
            } elseif ($result instanceof ViewInterface) {
                $vars = $result->getVars() + [
                    'debug' => !empty($this->settings['view']['debug'])
                ];
                $response->write($this->renderer->render($result->getTemplate(), $vars));
            }

            return $response;
        }

        throw new BadMethodCallException();
    }
}