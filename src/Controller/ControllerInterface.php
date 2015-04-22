<?php
namespace Controller;

use View\RendererInterface;

interface ControllerInterface
{
    /**
     * Set view renderer
     *
     * @param View\RendererInterface $renderer Renderer
     */
    public function setRenderer(RendererInterface $renderer);
}