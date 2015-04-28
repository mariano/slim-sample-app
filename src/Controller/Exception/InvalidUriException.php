<?php
namespace Controller\Exception;

use Psr\Http\Message\UriInterface;

class InvalidUriException extends SecurityException
{
    public function __construct(UriInterface $uri)
    {
        $path = $uri->getPath();
        parent::__construct("Invalid URI: {$path}");
    }
}