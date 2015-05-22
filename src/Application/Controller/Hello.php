<?php
namespace Application\Controller;

use Application\View\View;

class Hello extends BaseController
{
    public function helloAction(array $args)
    {
        return new View('hello.html', [
            'name' => $args['name']
        ]);
    }
}