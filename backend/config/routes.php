<?php
declare(strict_types=1);

use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;

return static function (RouteBuilder $routes): void {
    $routes->setRouteClass(DashedRoute::class);

    $routes->prefix('Api', function (RouteBuilder $builder): void {
    $builder->setExtensions(['json', 'xml']);

    // 1. Rotas estáticas / nominais de Employees (vêm SEMPRE ANTES de rotas com {id})
    $builder->connect('/employees/get-all-employees', [
        'controller' => 'Employees',
        'action' => 'getAllEmployees',
    ])->setMethods(['GET', 'OPTIONS']);

    $builder->connect('/employees', [
        'controller' => 'Employees',
        'action' => 'getAllEmployees',
    ])->setMethods(['GET', 'OPTIONS']);

    // createEmployee usando connect para permitir POST e OPTIONS simultaneamente
    $builder->connect('/employees/create-employee', [
        'controller' => 'Employees',
        'action' => 'createEmployee',
    ])->setMethods(['POST', 'OPTIONS']);

    // 2. Rota dinâmica com {id} (SEMPRE DEPOIS das rotas fixas acima)
    $builder->connect('/employees/{id}', [
        'controller' => 'Employees',
        'action' => 'deleteEmployee',
    ])
    ->setMethods(['DELETE', 'OPTIONS'])
    ->setPatterns(['id' => '\d+'])
    ->setPass(['id']);

    // 3. Fallbacks
    $builder->fallbacks(DashedRoute::class);
});

    $routes->scope('/', function (RouteBuilder $builder): void {
        $builder->connect('/', ['controller' => 'Pages', 'action' => 'display', 'home']);
        $builder->connect('/pages/*', 'Pages::display');
        $builder->fallbacks();
    });
};