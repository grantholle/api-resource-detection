<?php

namespace GrantHolle\Http\Resources\Traits;

use GrantHolle\Http\Resources\JsonResource;
use Illuminate\Container\Container;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Throwable;

trait HasResource
{
    /**
     * Get a new resource instance for the given resource(s).
     *
     * @param mixed ...$parameters
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    public static function resource(...$parameters)
    {
        return static::newResource(...$parameters)
            ?: static::resourceForModel(get_called_class(), ...$parameters);
    }

    /**
     * Create a new resource instance for the model.
     *
     * @param static|null $model
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    protected static function newResource($model = null)
    {
       //
    }

    /**
     * Get the resource representation of the model.
     *
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    public function toResource()
    {
        return static::resource($this);
    }

    protected static function resourceForModel($model, ...$parameters)
    {
        $resource = static::resolveResourceName($model);

        if (($parameters[0] ?? null) instanceof Collection) {
            return $resource::collection($parameters[0]);
        }

        return $resource::make(...$parameters);
    }

    /**
     * Get the namespace for the application's API resources.
     *
     * @return string
     */
    public static function resolveResourceNamespace()
    {
        $resolver = JsonResource::$resourceNamespaceResolver ?: function () {
            $appNamespace = static::appNamespace();

            return $appNamespace.'Http\\Resources\\';
        };

        return $resolver();
    }

    /**
     * Get the resource name for the given model name.
     *
     * @param  string  $modelName
     * @return string
     */
    public static function resolveResourceName(string $modelName)
    {
        $resolver = JsonResource::$resourceNameResolver ?: function (string $modelName) {
            $modelName = class_basename($modelName);
            $resourceNamespace = static::resolveResourceNamespace();
            $resourceName = Str::endsWith($resourceNamespace, '\\')
                ? $resourceNamespace.$modelName
                : $resourceNamespace.'\\'.$modelName;

            return class_exists($resourceName)
                ? $resourceName
                : $resourceName.'Resource';
        };

        return $resolver($modelName);
    }

    /**
     * Get the application namespace for the application.
     *
     * @return string
     */
    protected static function appNamespace(): string
    {
        try {
            return Container::getInstance()
                ->make(Application::class)
                ->getNamespace();
        } catch (Throwable $e) {
            return 'App\\';
        }
    }
}
