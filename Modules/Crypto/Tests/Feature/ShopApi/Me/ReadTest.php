<?php

namespace Modules\Crypto\Tests\Feature\ShopApi\Me;

use Illuminate\Support\Facades\Auth;
use Illuminate\Testing\Fluent\AssertableJson;
use Modules\Passport\Models\User;
use Modules\Passport\Tests\TestCase;

class ReadTest extends TestCase
{
    public function testIndex()
    {
        $user = User::first();

        Auth::login($user);

        $response = $this->getJson(route('api.me.index'));

        $response->assertStatus(200)
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has(
                    'data',
                    fn (AssertableJson $json) =>
                    $json->where('id', $user->uuid)->where('email', $user->email)->etc()
                )
            );
    }
}
