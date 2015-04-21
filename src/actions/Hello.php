<?php
namespace actions;

use Action\BaseAction;
use View\View;

class Hello extends BaseAction
{
    public function hello(array $args)
    {
        return new View('hello.html', [
            'name' => $args['name']
        ]);
    }
}