<?php
namespace Controller;

use BadMethodCallException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use View\RendererInterface;
use View\ViewInterface;

abstract class BaseController
{
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
     * Renderer
     *
     * @var View\RendererInterface
     */
    private $renderer;

    /**
     * View renderer
     *
     * @var View\RendererInterface
     */

    public function __construct(RendererInterface $renderer)
    {
        $this->renderer = $renderer;
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
                    'debug' => true
                ];
                $response->write($this->renderer->render($result->getTemplate(), $vars));
            }

            return $response;
        }

        throw new BadMethodCallException();
    }
}