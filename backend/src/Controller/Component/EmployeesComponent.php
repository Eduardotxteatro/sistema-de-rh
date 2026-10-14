<?php
declare(strict_types=1);

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Datasource\ConnectionManager;
use Cake\Http\ServerRequest;
use RuntimeException;

class EmployeesComponent extends Component
{
    public function normalizeInput(array $input): array
    {
        $result = [];

        $fieldMap = [
            'employee_id' => 'EMPLOYEE_ID',
            'first_name' => 'FIRST_NAME',
            'last_name' => 'LAST_NAME',
            'email' => 'EMAIL',
            'phone_number' => 'PHONE_NUMBER',
            'hire_date' => 'HIRE_DATE',
            'job_id' => 'JOB_ID',
            'salary' => 'SALARY',
            'commission_pct' => 'COMMISSION_PCT',
            'manager_id' => 'MANAGER_ID',
            'department_id' => 'DEPARTMENT_ID',
        ];

        foreach ($input as $key => $value) {
            if (isset($fieldMap[$key])) {
                $result[$fieldMap[$key]] = $value;
            }
        }

        return $result;
    }

    public function extractListRequest (array $requestData): array
    {
        $data = $requestData['data'] ?? [];

        $rawFilters = $data['filters'] ?? [];
        $filters = [];

        if (isset($rawFilters['field']) && array_key_exists('value', $rawFilters)) {
            $filters[$rawFilters['field']] = $rawFilters['value'];
        } elseif (is_array($rawFilters)) {
            $filters = $rawFilters;
        }

        $normalizedFilters = $this->normalizeInput($filters);

        $rawLimit = $data['limit'] ?? $data['rows'] ?? $requestData['limit'] ?? $requestData['rows'] ?? 10;
        $rawOffset = $data['offset'] ?? $data['first'] ?? $requestData['offset'] ?? $requestData['first'] ?? 0;

        $direction = strtoupper((string)($data['direction'] ?? $requestData['direction'] ?? 'ASC'));
        if (!in_array($direction, ['ASC', 'DESC'], true)) {
            $direction = 'ASC';
        }

        $order = $data['order'] ?? $requestData['order'] ?? 'TIPO_HORARIO';
        if (is_array($order) && isset($order[0]['field'])) {
            $order = $order[0]['field'];
            $direction = ((int)($data['order'][0]['order'] ?? 1) === -1) ? 'DESC' : 'ASC';
        }

        $normalizedOrder = $this->normalizeInput([(string)$order => true]);
        $normalizedOrderKeys = array_keys($normalizedOrder);
        $orderField = !empty($normalizedOrderKeys) ? $normalizedOrderKeys[0] : $order;
        $allowedOrderFields = [
            'EMPLOYEE_ID', 
            'FIRST_NAME', 
            'LAST_NAME', 
            'EMAIL', 
            'PHONE_NUMBER',
            'HIRE_DATE', 
            'JOB_ID', 
            'SALARY', 
            'COMMISSION_PCT', 
            'MANAGER_ID', 
            'DEPARTMENT_ID'
        ];
        if (!in_array($orderField, $allowedOrderFields, true)) {
            $orderField = 'EMPLOYEE_ID';
        }
        return [
            'filters' => $normalizedFilters,
            'limit' => max(1, (int)$rawLimit),
            'offset' => max(0, (int)$rawOffset),
            'orderField' => $orderField,
            'direction' => $direction,
        ];
    }

    public function formatForFrontend(array $employee): array
    {
        return [
            'employee_id' => $employee['EMPLOYEE_ID'] ?? null,
            'first_name' => $employee['FIRST_NAME'] ?? null,
            'last_name' => $employee['LAST_NAME'] ?? null,
            'email' => $employee['EMAIL'] ?? null,
            'phone_number' => $employee['PHONE_NUMBER'] ?? null,
            'hire_date' => isset($employee['HIRE_DATE']) ? date('Y-m-d H:i:s', strtotime($employee['HIRE_DATE'])) : null,
            'job_id' => $employee['JOB_ID'] ?? null,
            'salary' => $employee['SALARY'] ?? null,
            'commission_pct' => $employee['COMMISSION_PCT'] ?? null,
            'manager_id' => $employee['MANAGER_ID'] ?? null,
            'department_id' => $employee['DEPARTMENT_ID'] ?? null,
        ];
    }
}