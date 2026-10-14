<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\AppController;
use Cake\Http\Response;
use App\Config\ResponseCodes;

class EmployeesController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();

        // Carrega o seu componente EmployeesComponents
        $this->loadComponent('Employees');
    }

    public function getAllEmployees(): Response
    {
        $this->request->allowMethod(['get']);

        $requestData = $this->request->getQueryParams();

        if (isset($requestData['filters']) && is_string($requestData['filters'])) {
            $requestData['filters'] = json_decode($requestData['filters'], true) ?? [];
        }

        // Extrai parâmetros com o Component
        $params = $this->Employees->extractListRequest($requestData);

        // 1. Obtém a Table compatível com CakePHP 4.1
        /** @var \App\Model\Table\EmployeesTable $employeesTable */
        $employeesTable = $this->getTableLocator()->get('Employees');
        
        $result = $employeesTable->getPaginated($params);

        $rows = array_map(function (array $emp): array {
            return [
                'EMPLOYEE_ID'   => $emp['EMPLOYEE_ID'] ?? $emp['employee_id'] ?? null,
                'FIRST_NAME'    => $emp['FIRST_NAME'] ?? $emp['first_name'] ?? null,
                'LAST_NAME'     => $emp['LAST_NAME'] ?? $emp['last_name'] ?? null,
                'EMAIL'         => $emp['EMAIL'] ?? $emp['email'] ?? null,
                'PHONE_NUMBER'  => $emp['PHONE_NUMBER'] ?? $emp['phone_number'] ?? null,
                'HIRE_DATE'     => isset($emp['HIRE_DATE']) ? date('Y-m-d', strtotime((string)$emp['HIRE_DATE'])) : null,
                'JOB_ID'        => $emp['JOB_ID'] ?? $emp['job_id'] ?? null,
                'SALARY'        => $emp['SALARY'] ?? $emp['salary'] ?? null,
                'DEPARTMENT_ID' => $emp['DEPARTMENT_ID'] ?? $emp['department_id'] ?? null,
            ];
        }, $result['data']);

        $payload = [
            'success' => true,
            'message' => 'Registos carregados com sucesso',
            'payload' => [
                'data'            => $rows,
                'totalRecords'    => $result['totalRecords'],
                'filteredRecords' => count($rows),
                'recordsTotal'    => $result['totalRecords'],
                'recordsFiltered' => count($rows),
            ],
        ];

        return $this->response
            ->withType('application/json')
            ->withStringBody((string)json_encode($payload));
    }

    public function deleteEmployee($id = null)
    {
        $this->autoRender = false;

        // Se for preflight OPTIONS, responde 200 de imediato
        if ($this->request->is('options')) {
            return $this->restResponse(ResponseCodes::OK, 'OK', null);
        }

        $this->request->allowMethod(['delete', 'options']);

        try {
            $employeeId = (int)$id;

            if (!$employeeId) {
                return $this->restResponse(ResponseCodes::BAD_REQUEST, 'EMPLOYEE_ID é obrigatório', null);
            }

            $affectedRows = $this->employeeTable->deleteEmployeeById($employeeId);
            if ($affectedRows === 0) {
                return $this->restResponse(ResponseCodes::DB_NO_DATA_FOUND, 'Registo não encontrado para eliminação', null);
            }

            return $this->restResponse(ResponseCodes::OK, 'Registo eliminado com sucesso', null);
        } catch (\Throwable $exception) {
            return $this->restResponse(ResponseCodes::DB_ERROR, 'Erro ao eliminar registo: ' . $exception->getMessage(), null);
        }
    }
}