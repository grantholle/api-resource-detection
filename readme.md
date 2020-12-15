# API Resource Detection

This package provides a trait to add to your Eloquent models to get automatic API Resource detection.

## Installation

```
composer require grantholle/api-resource-detection 
```

## Usage

You generate resources for your models by using the `php artisan make:resource` command. This will create a resource in your `app/Http/Resources` directory. This package detects different naming conventions. You can name your resources the same as your model or name it using the `Resource` suffix, like `ModelResource` In your models that have resources, you can add the `HasResource` trait.

Assume we have a resource named `UserResource`.

```
php artisan make:resource UserResource
```

Let's add the trait to the `User` model:

```php
namespace App\Models;

use GrantHolle\Http\Resources\Traits\HasResource;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasResource;

    protected $guarded = [];
}
```

Now when you want to get the resource for the model, you can call `toResource()` on the model instance to detect and retrieve the API resource for the model.

```php
$user = User::find(1);

// This will be a UserResource instance
$user->toResource();
```

You can also use the alternative syntax of `Model::resource()`.

```php
$user = User::find(1);

User::resource($user);
```

This also works for collection of models.

```php
$users = User::all();

User::resource($users);
```

This will automatically look for a `UserCollection` or `UserCollectionResource` resource class. If none is found it will default to calling the `collection()` function on the standard resource class.

If your application has a different namespace other than the default resource namespace, you can tell it to use a different namespace for your resources.

In a service provider,

```php
public function boot() {
    \GrantHolle\Http\Resources\JsonResource::resolveResourceNamespaceUsing(function () {
        return 'My\\Resource\\Namespace\\';
    });
}
```

You can also change how the resources is guessed and return a new class name based on the model. By default, it looks for your model name or model name with the `Resource` suffix in the default resource namespace. You can also customize how collections are resolved.

```php
public function boot() {
    \GrantHolle\Http\Resources\JsonResource::guessResourceNamesUsing(function ($modelClass) {
        $modelName = class_basename($modelClass);
        
        // This will return a resource with the class name
        // ModelApiResource in the `My\Resource\Namespace` namespace.
        return 'My\\Resource\\Namespace\\' . $modelName . 'ApiResource';
    });
    
    \GrantHolle\Http\Resources\JsonResource::guessResourceCollectionNamesUsing(function ($modelClass) {
        $modelName = class_basename($modelClass);
        
        // This will return a resource with the class name
        // ModelCollectionApiResource in the `My\Resource\Namespace` namespace.
        return 'My\\Resource\\Namespace\\' . $modelName . 'CollectionApiResource';
    });
}
```
