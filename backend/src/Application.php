<?php
declare(strict_types=1);

namespace App;

use Cake\Core\Configure;
use Cake\Core\Exception\MissingPluginException;
use Cake\Error\Middleware\ErrorHandlerMiddleware;
use Cake\Http\BaseApplication;
use Cake\Http\Middleware\BodyParserMiddleware;
use Cake\Http\MiddlewareQueue;
use Cake\Routing\Middleware\AssetMiddleware;
use Cake\Routing\Middleware\RoutingMiddleware;
use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Application extends BaseApplication
{
    public function bootstrap(): void
    {
        $this->addPlugin('CakeDC/OracleDriver');

        parent::bootstrap();

        if (PHP_SAPI === 'cli') {
            $this->bootstrapCli();
        }

        if (Configure::read('debug')) {
            $this->addPlugin('DebugKit');
        }
    }

    public function middleware(MiddlewareQueue $middlewareQueue): MiddlewareQueue
    {
        $middlewareQueue
            // 1. ErrorHandler deve vir em primeiro lugar
            ->add(new ErrorHandlerMiddleware(Configure::read('Error')))

            // 2. Middleware de CORS
            ->add(function (ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface {
                $origin = $request->getHeaderLine('Origin');
                $allowedOrigins = ['http://localhost:4200', 'http://localhost:4201'];
                $isAllowed = in_array($origin, $allowedOrigins, true);

                // Pré-validação OPTIONS (Preflight)
                if ($request->getMethod() === 'OPTIONS') {
                    $response = new \Cake\Http\Response();
                    if ($isAllowed) {
                        $response = $response
                            ->withHeader('Access-Control-Allow-Origin', $origin)
                            ->withHeader('Vary', 'Origin')
                            ->withHeader('Access-Control-Allow-Credentials', 'true');
                    }
                    return $response
                        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
                        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Authorization, Origin, Accept')
                        ->withHeader('Access-Control-Max-Age', '86400')
                        ->withStatus(200);
                }

                // Executa a requisição real
                $response = $handler->handle($request);

                if ($isAllowed) {
                    $response = $response
                        ->withHeader('Access-Control-Allow-Origin', $origin)
                        ->withHeader('Vary', 'Origin')
                        ->withHeader('Access-Control-Allow-Credentials', 'true');
                }

                return $response;
            })

            // 3. Middlewares restantes
            ->add(new AssetMiddleware([
                'cacheTime' => Configure::read('Asset.cacheTime'),
            ]))
            ->add(new RoutingMiddleware($this))
            ->add(new BodyParserMiddleware());

        return $middlewareQueue;
    }

    /**
     * Carrega as rotas da aplicação lendo o arquivo config/routes.php
     */
    public function routes(RouteBuilder $routes): void
    {
        $routes->setRouteClass(DashedRoute::class);

        // Carrega plugins primeiro
        parent::routes($routes);

        // Carrega e executa diretamente o routes.php com a instância de $routes
        $routesFile = CONFIG . 'routes.php';
        if (file_exists($routesFile)) {
            $routesCallback = require $routesFile;
            if (is_callable($routesCallback)) {
                $routesCallback($routes);
            }
        }
    }

    protected function bootstrapCli(): void
    {
        try {
            $this->addPlugin('Bake');
        } catch (MissingPluginException $e) {
            // Do not halt if the plugin is missing
        }

        $this->addPlugin('Migrations');
    }
}