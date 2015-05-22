<?php
namespace Application\View;

interface RendererInterface
{
    /**
     * Render Template
     *
     * This method will output the rendered template content
     *
     * @param string $template The path to the template
     * @param array $data
     * @return string Rendered content
     */
    public function render($template, array $data = null);
}