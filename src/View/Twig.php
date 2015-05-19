<?php
namespace View;

use Pimple\Container;
use Slim\Views\Twig as SlimTwig;

class Twig implements RendererInterface
{
    /**
     * @var Slim\Views\Twig
     */
    private $twig;

    /**
     * Create instance
     *
     * @param Container Pimple container
     * @param string $path Path to templates
     * @param array $settings Twig settings
     * @param Slim\Views\Twig $twig Twig
     */
    public function __construct(Container $container, $path, array $settings = [])
    {
        $this->twig = new SlimTwig($path, $settings);
        $this->twig->register($container);
    }

    /**
     * Render Twig Template
     *
     * This method will output the rendered template content
     *
     * @param string $template The path to the Twig template, relative to the Twig templates directory.
     * @param null $data
     * @return string
     */
    public function render($template, array $data = null)
    {
        return $this->twig->fetch($template, $data);
    }
}