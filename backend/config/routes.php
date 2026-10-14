<?php
declare(strict_types=1);

use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;

return static function (RouteBuilder $routes): void {
    $routes->setRouteClass(DashedRoute::class);

    $routes->prefix('Api', function (RouteBuilder $builder): void {
        $builder->setExtensions(['json', 'xml']);

        // 1. GET lista de todos os funcionários
        $builder->get('/employees/get-all-employees', [
            'controller' => 'Employees',
            'action' => 'getAllEmployees',
        ]);

        $builder->get('/employees', [
            'controller' => 'Employees',
            'action' => 'getAllEmployees',
        ]);

        // 2. DELETE /api/employees/{id} -> Mapeia o {id} diretamente para o argumento da action
        $builder->connect('/employees/{id}', [
            'controller' => 'Employees',
            'action' => 'deleteEmployee',
        ])
        ->setMethods(['DELETE', 'OPTIONS'])
        ->setPatterns(['id' => '\d+'])
        ->setPass(['id']);

        // 3. Fallbacks apenas para rotas padrão que não colidam com parâmetros numéricos
        $builder->fallbacks(DashedRoute::class);
    });

    $routes->scope('/', function (RouteBuilder $builder): void {
        $builder->connect('/', ['controller' => 'Pages', 'action' => 'display', 'home']);
        $builder->connect('/pages/*', 'Pages::display');
        $builder->fallbacks();
    });
};