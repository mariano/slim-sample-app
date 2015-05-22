<?php
namespace Application\View;

interface ViewInterface
{
    /**
     * Get the template to render
     *
     * @return string Template
     */
    public function getTemplate();

    /**
     * Get the vars to render with
     *
     * @return array Variables
     */
    public function getVars();
}