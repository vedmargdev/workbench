<?php

namespace Tests;

use Orchestra\Testbench\TestCase;

class DeskslipTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            \LaravelDeskslip\LaravelDeskslipServiceProvider::class,
        ];
    }

    /** @test */
    public function test_deskslip_route_accessible()
    {
        $response = $this->get('/laravel-deskslip');

        $response->assertStatus(200);
    }
}
