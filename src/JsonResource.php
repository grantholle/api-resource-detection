<?php

namespace GrantHolle\Http\Resources;

class JsonResource
{
    /**
     * The resource name resolver.
     *
     * @var callable
     */
    public static $resourceNameResolver;

    /**
     * The resource collection name resolver.
     *
     * @var callable
     */
    public static $resourceCollectionNameResolver;

    /**
     * The resource namespace resolver.
     *
     * @var callable
     */
    public static $resourceNamespaceResolver;

    /**
     * Specify the callback that should be invoked to guess the resource name.
     *
     * @param callable|null $callback
     * @return void
     */
    public static function guessResourceNamesUsing(callable $callback = null)
    {
        static::$resourceNameResolver = $callback;
    }

    /**
     * Specify the callback that should be invoked to guess resource collection names.
     *
     * @param callable|null $callback
     * @return void
     */
    public static function guessResourceCollectionNamesUsing(callable $callback = null)
    {
        static::$resourceCollectionNameResolver = $callback;
    }

    /**
     * Specify the callback that should be invoked to get the namespace of the API resources.
     *
     * @param callable|null $callback
     * @return void
     */
    public static function resolveResourceNamespaceUsing(callable $callback = null)
    {
        static::$resourceNamespaceResolver = $callback;
    }
}
