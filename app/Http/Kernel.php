<?php
namespace App\Http {

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    // ...existing code...

    protected $routeMiddleware = [
        // Other middleware...
        'role' => \App\Http\Middleware\Role::class,
    ];

    // ...existing code...
}

}