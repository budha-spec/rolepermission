## Getting started

Please check the official laravel installation guide for server requirements before you start. [Official Documentation](https://laravel.com/docs/11.x/installation)

## About RolePermission
	A lightweight and customizable Role & Permission management system tailored for Laravel admin panels—built with no third-party dependencies.
	You can define your own modules and sub-modules, create custom roles, and assign specific permissions to those roles.
	Once permissions are set, simply assign the role to a user to control access.

## Installation

Install the package via Composer:

```
composer require budhaspec/rolepermission
```

Run the database migrations (ensure your .env file is configured properly):

```
php artisan migrate
```

Publish the public assets:

```
php artisan vendor:publish --tag=public
```

Update user model as below.

```
use Budhaspec\Rolepermission\Models\Role;

Add 'role_id' in $fillable array.

Add below relationship.

public function role() {
    return $this->belongsTo(Role::class, 'role_id');
}
```

Start the local development server:

```
php artisan serve
```

You can now access the server at http://127.0.0.1:8000/

## Usage

## Middleware & Route Protection

Configure middleware in bootstrap/app.php

```
use Budhaspec\Rolepermission\Http\middleware\CheckPermission;

->withMiddleware(function (Middleware $middleware) {
      $middleware->alias([
          'role-permission' => CheckPermission::class,
      ]);
  })
```

Wrap any routes you want protected by roles and permissions inside the role-permission middleware:

```
Route::middleware(['auth', 'role-permission'])->group(function () {
	  Route::get('/product/list', fn () => 'Outer Product List')->name('product.list');
    Route::get('/product/add', fn () => 'Outer Product add')->name('product.add');
});
```

Only users with the appropriate role and permission will be able to access the routes inside this group.

## Modules

Manage modules and sub-modules from:

http://127.0.0.1:8000/modules

This section allows you to define features (modules) and their actions (sub-modules) that need to be permission-controlled.

## Roles

Manage roles and assign permissions via:

http://127.0.0.1:8000/roles

From here, you can:
  - Create and edit roles
  - Assign module and sub-module permissions to roles
  - Assign roles to users

## Permissions Mapping Convention

```
Important: THE NAMED ROUTE MUST MATCH THE MODULE.SUB-MODULE NAME; otherwise, IT WON'T WORK.
```

From the roles section you can manage roles, Assign permissions (module/sub module) to specific role by clicking on 'Assign Permission' button.  

You can assign that Role to specific users for given a grant of that module/sub module.

```
Suppose product is module and list, add, edit and delete are sub modules assigned to manager role. Manager role assigned to budha.test@gmail.com user then that user can access product and it's sub module list, add, edit and delete routes.

 Consider the below test cases for product module.

 Correct Format:
 	Route::get('/product/list', fn () => 'Product List')->name('product.list');

 Incorrect Formats:
  	Route::get('/product/list', fn () => 'Product List')->name('product.listing');  // Invalid
	  Route::get('/product/list', fn () => 'Product List')->name('products');         // Invalid

 ------------------------------------------------------------------------------------------------------------------------

 | Module  | Sub-Module | Valid Route Name | Invalid Examples                      |
 | ------- | ---------- | ---------------- | ------------------------------------- |
 | product | list       | `product.list`   | `product.listing`, `products.listing` |
 | product | add        | `product.add`    | `product.create`, `product.insert`    |
 | product | edit       | `product.edit`   | `product.update`                      |
 | product | delete     | `product.delete` | `product.destroy`, `product.remove`   |

 Important: In short, named route must be module.sub module name like 'product.list' then it will work.

 Example:
	If the module is product and sub-module is edit, then the named route must be product.edit

	Route::get('/product/{id}/edit', fn () => 'Edit Product')->name('product.edit'); // ✅ Valid
```