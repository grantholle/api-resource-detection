<?php

namespace GrantHolle\Http\Tests;

use GrantHolle\Http\Resources\JsonResource;
use GrantHolle\Http\Tests\Fixtures\User;
use GrantHolle\Http\Tests\Fixtures\UserResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Orchestra\Testbench\TestCase;

class ResourceDetectionTest extends TestCase
{
    protected function tearDown(): void
    {
        parent::tearDown();

        JsonResource::guessResourceNamesUsing();
        JsonResource::resolveResourceNamespaceUsing();
    }

    public function test_can_resolve_to_resource_from_model_instance_using_guess_function()
    {
        JsonResource::guessResourceNamesUsing(function ($model) {
            return UserResource::class;
        });

        $user = new User([
            'id' => 1,
            'name' => 'Name',
        ]);

        $resource = $user->toResource();

        $this->assertInstanceOf(UserResource::class, $resource);
    }

    public function test_can_resolve_resource_from_model_instance_using_guess_function()
    {
        JsonResource::guessResourceNamesUsing(function ($model) {
            return UserResource::class;
        });

        $user = new User([
            'id' => 1,
            'name' => 'Name',
        ]);

        $resource = User::resource($user);

        $this->assertInstanceOf(UserResource::class, $resource);
    }

    public function test_can_get_resource_for_collection()
    {
        JsonResource::guessResourceNamesUsing(function ($model) {
            return UserResource::class;
        });

        $users = collect([
            new User(['id' => 1]),
            new User(['id' => 2]),
            new User(['id' => 3]),
        ]);

        $resource = User::resource($users);

        $this->assertInstanceOf(AnonymousResourceCollection::class, $resource);
    }

    public function test_can_guess_with_namespace_provided_without_trailing_slashes()
    {
        JsonResource::resolveResourceNamespaceUsing(function () {
            return 'GrantHolle\\Http\\Tests\\Fixtures\\Resources';
        });

        $this->assertInstanceOf(
            \GrantHolle\Http\Tests\Fixtures\Resources\User::class,
            (new User)->toResource()
        );
    }

    public function test_can_guess_with_namespace_provided_with_trailing_slashes()
    {
        JsonResource::resolveResourceNamespaceUsing(function () {
            return 'GrantHolle\\Http\\Tests\\Fixtures\\Resources\\';
        });

        $this->assertInstanceOf(
            \GrantHolle\Http\Tests\Fixtures\Resources\User::class,
            (new User)->toResource()
        );
    }

    public function test_resolves_to_correct_default_namespace()
    {
        try {
            (new User)->toResource();
        } catch (\Throwable $e) {
            $this->assertStringContainsStringIgnoringCase(
                "Class 'App\Http\Resources\UserResource' not found",
                $e->getMessage()
            );
        }
    }
}
