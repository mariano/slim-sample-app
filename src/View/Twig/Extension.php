<?php
/**
 * Slim - a micro PHP 5 framework
 *
 * @author      Josh Lockhart
 * @author      Andrew Smith
 * @link        http://www.slimframework.com
 * @copyright   2013 Josh Lockhart
 * @version     0.1.3
 * @package     SlimViews
 *
 * MIT LICENSE
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
 * LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 * WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
namespace View\Twig;

use Slim\Http\Uri;
use Slim\Interfaces\RouterInterface;

class Extension extends \Twig_Extension
{
    private $baseUri;
    private $router;

    public function __construct(Uri $uri, RouterInterface $router)
    {
        $scheme = $uri->getScheme();
        $authority = $uri->getAuthority();
        $basePath = $uri->getBasePath();

        $this->baseUri = ($scheme ? $scheme . '://' : '') . $authority . ltrim($basePath, '/');
        $this->router = $router;
    }

    public function getName()
    {
        return 'slim';
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('asset', [$this, 'asset']),
            new \Twig_SimpleFunction('urlFor', [$this, 'urlFor']),
        ];
    }

    public function asset($url)
    {
        return $this->site('assets/' . $url);
    }

    public function urlFor($name, array $params = [])
    {
        return $this->router->urlFor($name, $params);
    }

    public function site($url, $withUri = true)
    {
        return $this->baseUri . '/' . ltrim($url, '/');
    }
}