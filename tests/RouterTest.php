<?php

namespace Dframe\Tests;

use Dframe\Router;
use PHPUnit\Framework\TestCase;

/**
 * Class RouterTest
 *
 * @package Dframe\Tests
 */
class RouterTest extends TestCase
{
    /**
     * @var Router
     */
    protected $router;

    /**
     *
     */
    public function setUp()
    {
        $this->router = new Router();

        $this->router->addRoute([
            'page/:page' => [
                'page/[page]/',
                'task=page&action=[page]',
            ],
        ]);

        $this->router->addRoute([
            'error/:code' => [
                'error/[code]/',
                'task=page&action=error&type=[code]',
                'args' => [
                    'code' => '[code]',
                ],
            ],
        ]);
        $this->router->addRoute([
            'default' => [
                '[task]/[action]/[params]',
                'task=[task]&action=[action]',
                'params' => '(.*)',
                '_params' => [
                    '[name]/[value]/',
                    '[name]=[value]',
                ],
            ],
        ]);
    }

    /**
     *
     */
    public function testRouterIsActive()
    {
        $_SERVER['REQUEST_URI'] = '';
        $this->assertSame(true, $this->router->isActive(''));
        $this->assertSame(false, $this->router->isActive('this-is-not-page-that-you-looking-for'));
    }

    /**
     *
     */
    public function testPublicWeb()
    {
        $this->assertSame('http://dframeframework.com/css/example.css', $this->router->publicWeb('css/example.css'));
        $this->assertSame('http://dframeframework.com/deep/css/example.css', $this->router->publicWeb('css/example.css', 'deep/'));
    }

    public function testMakeUrl()
    {
        $this->assertSame('http://dframeframework.com/page/index', $this->router->makeUrl('page/:page?page=index'));
        $this->assertSame('http://test.com/page/index', $this->router->domain('test.com')->makeUrl('page/:page?page=index'));
        $this->assertSame('http://test.dframeframework.com/page/index', $this->router->subdomain('test')->makeUrl('page/:page?page=index'));
        $this->assertSame('https://dframeframework.com/page/index', $this->router->setHttps(true)->makeUrl('page/:page?page=index'));
        $this->assertSame('http://dframeframework.com/page/index', $this->router->setHttps(false)->makeUrl('page/:page?page=index'));
    }
}
