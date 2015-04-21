<?php
namespace actions;

use Slim\App;

class Hello
{
    protected $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    public function hello(\Slim\Http\Request $request, \Slim\Http\Response $response, array $args)
    {
        $response->write($this->app['view']->render('hello.html', [
            'name' => $args['name'],
            'debug' => true
        ]));
        return $response;
    }
}