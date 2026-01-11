<?php

namespace Polirium\Tests;

class RoutesTest extends TestCase
{
    private $routes;

    public function setUp(): void
    {
        parent::setUp();

        $this->routes = $this->app['router']->getRoutes();
    }

    /** @test */
    function it_adds_impersonate_route()
    {
        $this->assertTrue((bool)$this->routes->getByName('impersonate'));
        $this->assertTrue((bool)$this->routes->getByAction('Polirium\Impersonate\Controllers\ImpersonateController@take'));
    }

    /** @test */
    function it_adds_leave_route()
    {
        $this->assertTrue((bool)$this->routes->getByName('impersonate.leave'));
        $this->assertTrue((bool)$this->routes->getByAction('Polirium\Impersonate\Controllers\ImpersonateController@leave'));
    }
}
