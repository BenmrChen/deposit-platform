<?php

namespace Modules\Crypto\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Modules\Passport\Database\Seeders\DatabaseSeeder;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use RefreshDatabase;

    protected $seeder = DatabaseSeeder::class;

    public function setUp(): void
    {
        parent::setUp();
        Cache::flush();
        Session::flush();
    }
}
