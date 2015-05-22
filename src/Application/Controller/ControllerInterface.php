<?php
namespace Application\Controller;

use Application\View\RendererInterface;

interface ControllerInterface
{
    /**
     * Set settings
     *
     * @param array $settings Settings
     */
    public function setSettings(array $settings);

    /**
     * Set view renderer
     *
     * @param View\RendererInterface $renderer Renderer
     */
    public function setRenderer(RendererInterface $renderer);
}