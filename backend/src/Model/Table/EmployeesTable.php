<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class EmployeesTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('HR.EMPLOYEES');
        $this->setPrimaryKey('EMPLOYEE_ID');
    }

    public function validationDefault(\Cake\Validation\Validator $validator): \Cake\Validation\Validator
    {
        $validator
            ->integer('EMPLOYEE_ID')
            ->allowEmptyString('EMPLOYEE_ID', null, 'create');

        $validator
            ->scalar('FIRST_NAME')
            ->maxLength('FIRST_NAME', 20)
            ->requirePresence('FIRST_NAME', 'create')
            ->notEmptyString('FIRST_NAME');

        $validator
            ->scalar('LAST_NAME')
            ->maxLength('LAST_NAME', 25)
            ->requirePresence('LAST_NAME', 'create')
            ->notEmptyString('LAST_NAME');

        $validator
            ->email('EMAIL')
            ->requirePresence('EMAIL', 'create')
            ->notEmptyString('EMAIL');

        $validator
            ->scalar('PHONE_NUMBER')
            ->maxLength('PHONE_NUMBER', 20)
            ->allowEmptyString('PHONE_NUMBER');

        $validator
            ->dateTime('HIRE_DATE')
            ->requirePresence('HIRE_DATE', 'create')
            ->notEmptyDateTime('HIRE_DATE');

        $validator
            ->scalar('JOB_ID')
            ->maxLength('JOB_ID', 10)
            ->requirePresence('JOB_ID', 'create')
            ->notEmptyString('JOB_ID');

        $validator
            ->numeric('SALARY')
            ->allowEmptyString('SALARY');

        $validator
            ->numeric('COMMISSION_PCT')
            ->allowEmptyString('COMMISSION_PCT');

        $validator
            ->integer('MANAGER_ID')
            ->allowEmptyString('MANAGER_ID');

        $validator
            ->integer('DEPARTMENT_ID')
            ->allowEmptyString('DEPARTMENT_ID');

        return $validator;
    }

    public function getEmployeeById($employeeId)
    {
        return $this->find()
            ->where(['EMPLOYEE_ID' => $employeeId])
            ->first();
    }

    public function getAllEmployees()
    {
        $conn = $this->getConnection();

        $sql = "
            SELECT DISTINCT e.EMPLOYEE_ID AS employee_id, 
                            e.FIRST_NAME AS first_name, 
                            e.LAST_NAME AS last_name, 
                            e.EMAIL AS email, 
                            e.PHONE_NUMBER AS phone_number, 
                            e.HIRE_DATE AS hire_date, 
                            e.JOB_ID AS job_id, 
                            e.SALARY AS salary, 
                            e.COMMISSION_PCT AS commission_pct, 
                            e.MANAGER_ID AS manager_id, 
                            e.DEPARTMENT_ID AS department_id
            FROM employees e
            ORDER BY e.EMPLOYEE_ID ASC
        ";
        return $conn->execute($sql)->fetchAll('assoc');
    }

    public function getPaginated(array $params): array
    {
        $conn = $this->getConnection();

        $limit = max(1, (int)($params['limit'] ?? 10));
        $offset = max(0, (int)($params['offset'] ?? 0));
        $direction = strtoupper((string)($params['direction'] ?? 'ASC')) === 'DESC' ? 'DESC' : 'ASC';
        
        $allowedColumns = [
            'EMPLOYEE_ID', 'FIRST_NAME', 'LAST_NAME', 'EMAIL',
            'PHONE_NUMBER', 'HIRE_DATE', 'JOB_ID', 'SALARY',
            'COMMISSION_PCT', 'MANAGER_ID', 'DEPARTMENT_ID'
        ];
        $orderCol = in_array($params['orderField'] ?? '', $allowedColumns, true) 
            ? $params['orderField'] 
            : 'EMPLOYEE_ID';

        // 1. Total de registros no HR.EMPLOYEES
        $totalResult = $conn->execute("SELECT COUNT(*) AS total FROM HR.EMPLOYEES")->fetch('assoc');
        $totalRecords = (int)($totalResult['TOTAL'] ?? $totalResult['total'] ?? 0);

        // 2. Query paginada
        $sql = "
            SELECT e.EMPLOYEE_ID, 
                   e.FIRST_NAME, 
                   e.LAST_NAME, 
                   e.EMAIL, 
                   e.PHONE_NUMBER, 
                   TO_CHAR(e.HIRE_DATE, 'YYYY-MM-DD') AS HIRE_DATE, 
                   e.JOB_ID, 
                   e.SALARY, 
                   e.COMMISSION_PCT, 
                   e.MANAGER_ID, 
                   e.DEPARTMENT_ID
            FROM HR.EMPLOYEES e
            ORDER BY e.{$orderCol} {$direction}
            OFFSET :offset ROWS FETCH NEXT :limit ROWS ONLY
        ";

        $data = $conn->execute($sql, [
            'offset' => $offset,
            'limit'  => $limit,
        ])->fetchAll('assoc');

        return [
            'data'         => $data,
            'totalRecords' => $totalRecords,
        ];
    }

    public function deleteEmployeeById(int $employeeId): int
    {
        
        $conn = $this->getConnection();

        $sql = "
            DELETE FROM HR.EMPLOYEES
            WHERE EMPLOYEE_ID = :employeeId
        ";

        error_log("[DEBUG SQL] Executando query para o ID: " . $employeeId);

        $statement = $conn->execute($sql, [
            'employeeid' => $employeeId
        ]);

        return $statement->rowCount();
    }

}