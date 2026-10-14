<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Controller\Controller;
use App\Config\ResponseCodes;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/4/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{
    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('FormProtection');`
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');

        //Libera chamadas do frontend Angular
        $this->response = $this->response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
            ->withHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization');

        /*
         * Enable the following component for recommended CakePHP form protection settings.
         * see https://book.cakephp.org/4/en/controllers/components/form-protection.html
         */
        //$this->loadComponent('FormProtection');
    }

    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);

        $origin = $this->request->getHeaderLine('Origin');
        $allowedOrigins = ['http://localhost:4200', 'http://localhost:4201']; //permissão de requests das urls

        if (in_array($origin, $allowedOrigins, true)) {
            $this->response = $this->response
                ->withHeader('Access-Control-Allow-Origin', $origin)
                ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
                ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Authorization, Origin, Accept')
                ->withHeader('Access-Control-Allow-Credentials', 'true')
                ->withHeader('Vary', 'Origin');
        }

        // Se o browser enviar OPTIONS antes do DELETE, responde logo aqui com 200
        if ($this->request->is('options')) {
            return $this->response->withStatus(200);
        }
    }

    public function restResponse(string $status = ResponseCodes::OK, string $message = '', mixed $payload = ''): ResponseCodes 
    {
        $this->autoRender = false;

        if ($status !== ResponseCodes::OK && $status !== ResponseCodes::WARN) {
            $logPayload = $this->sanitizePayloadForLog($payload);
            $this->log(
                sprintf(
                    '[API Error %s] %s %s - %s | Payload: %s',
                    $status,
                    $this->request ? $this->request->getMethod() : 'UNKNOWN',
                    $this->request ? $this->request->getRequestTarget() : 'UNKNOWN',
                    $message,
                    $logPayload
                ),
                'error'
            );
        }

        $response = ['status' => $status, 'message' => $message, 'payload' => $payload];

        $origin = $this->request->getHeaderLine('Origin');
        $allowedOrigins = ['http://localhost:4200', 'http://localhost:4201'];

        $res = $this->response
            ->withType('application/json')
            ->withStringBody((string)json_encode($response));

        if (in_array($origin, $allowedOrigins, true)) {
            $res = $res
                ->withHeader('Access-Control-Allow-Origin', $origin)
                ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
                ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Authorization, Origin, Accept')
                ->withHeader('Access-Control-Allow-Credentials', 'true')
                ->withHeader('Vary', 'Origin');
        }

        $this->response = $res;
        return $this->res;
    }

    /**
     * Sanitiza o payload para escrita em logs, truncando strings longas (ex: imagens base64)
     *
     * @param mixed $payload
     * @return string
     */
    protected function sanitizePayloadForLog(mixed $payload): string
    {
        if (is_string($payload)) {
            return strlen($payload) > 500 ? substr($payload, 0, 500) . '... [TRUNCATED ' . strlen($payload) . ' bytes]' : $payload;
        }

        if (!is_array($payload)) {
            return (string)json_encode($payload);
        }

        $sanitized = $payload;
        array_walk_recursive($sanitized, function (&$value, $key) {
            if (is_string($value) && (strlen($value) > 200 || strtolower((string)$key) === 'logotipo')) {
                $value = substr($value, 0, 50) . '... [length: ' . strlen($value) . ' bytes]';
            }
        });

        $encoded = json_encode($sanitized);
        if ($encoded === false) {
            return '[Failed to encode payload]';
        }

        if (strlen($encoded) > 2000) {
            return substr($encoded, 0, 2000) . '... [TRUNCATED ' . strlen($encoded) . ' bytes]';
        }

        return $encoded;
    }

    
}
