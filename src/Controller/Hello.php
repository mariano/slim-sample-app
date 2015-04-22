<?php
namespace Controller;

use View\View;

class Hello extends BaseController
{
    public function helloAction(array $args)
    {
        return new View('hello.html', [
            'name' => $args['name']
        ]);
    }
}