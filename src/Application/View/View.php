<?php
namespace Application\View;

class View implements ViewInterface
{
    /**
     * Template
     *
     * @var string
     */
    private $template;

    /**
     * Variables
     *
     * @var array
     */
    private $vars;

    /**
     * Build a new view
     *
     * @param string $template Templae
     * @param array $vars Variables
     */
    public function __construct($template, array $vars = [])
    {
        $this->template = $template;
        $this->vars = $vars;
    }

    /**
     * Get the template to render
     *
     * @return string Template
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * Get the vars to render with
     *
     * @return array Variables
     */
    public function getVars()
    {
        return $this->vars;
    }
}