<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class Employee extends Entity 
{
    protected $_accessible = [
        'EMPLOYEE_ID' => true,
        'FIRST_NAME' => true,
        'LAST_NAME' => true,
        'EMAIL' => true,
        'PHONE_NUMBER' => true,
        'HIRE_DATE' => true,
        'JOB_ID' => true,
        'SALARY' => true,
        'COMMISSION_PCT' => true,
        'MANAGER_ID' => true,
        'DEPARTMENT_ID' => true    
    
    ];
}