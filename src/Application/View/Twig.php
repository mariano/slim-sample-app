<?php
namespace Application\View;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Slim\Views\Twig as SlimTwig;

class Twig implements RendererInterface, ServiceProviderInterface
{
    /**
     * @var SlimTwig
     */
    private $twig;

    /**
     * Create instance
     *
     * @param string $path Path to templates
     * @param array $settings Twig settings
     */
    public function __construct($path, array $settings = [])
    {
        $this->twig = new SlimTwig($path, $settings);
    }

    /**
     * Render Twig Template
     *
     * This method will output the rendered template content
     *
     * @param string $template The path to the Twig template, relative to the Twig templates directory.
     * @param array|null $data
     * @return string
     */
    public function render($template, array $data = null)
    {
        return $this->twig->fetch($template, $data);
    }

    /**
     * Registers services on the given container.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param Container $pimple An Container instance
     */
    public function register(Container $pimple)
    {
        $this->twig->register($pimple);
    }
}