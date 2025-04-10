// Add these to the $routeMiddleware array
protected $routeMiddleware = [
    // ... existing middleware
    'admin' => \App\Http\Middleware\AdminMiddleware::class,
    'customer' => \App\Http\Middleware\CustomerMiddleware::class,
    'role.redirect' => \App\Http\Middleware\RoleRedirect::class,
];