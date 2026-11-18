import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { MessageService } from 'primeng/api';
import { DynamicDialogConfig, DynamicDialogRef } from 'primeng/dynamicdialog';
import { EmployeeService } from '../services/employee.service';

@Component({
  selector: 'app-employees-form-dialog',
  templateUrl: './employees-form-dialog.component.html',
  styleUrls: ['./employees-form-dialog.component.scss']
})

export class EmployeesFormDialogComponent implements OnInit{

  idOriginal: any;
  employeeForm!: FormGroup;
  dataEmployee: any[] = [];
  employee: any[] = [];

  constructor(
    private employeeService: EmployeeService,
    private formBuilder: FormBuilder,
    private messageService: MessageService,
    public ref: DynamicDialogRef,
    public config: DynamicDialogConfig,
  ){}

  ngOnInit(): void {
    this.initForm();

    this.setEmployeeData(this.config.data);
    
  }
  initForm() {
    this.employeeForm = this.formBuilder.group({
      FIRST_NAME:     ["", Validators.required],
      LAST_NAME:      ["", Validators.required],
      EMAIL:          ["", [Validators.required, Validators.email]],
      PHONE_NUMBER:   ["", Validators.required],
      HIRE_DATE:      [null, Validators.required], // inicializado com null
      JOB_ID:         ["", Validators.required],
      SALARY:         [null],
      COMMISSION_PCT: [null],
      MANAGER_ID:     [null],
      DEPARTMENT_ID:  [null]
    });
  }
  setEmployeeData(data: any) {
    const employee = data?.employeeData;

    if (data?.type === 'edit' && employee) {
      this.employeeForm.patchValue({
        FIRST_NAME:     employee.FIRST_NAME,
        LAST_NAME:      employee.LAST_NAME,
        EMAIL:          employee.EMAIL,
        PHONE_NUMBER:   employee.PHONE_NUMBER, // Corrigido de employee.EMAIL
        HIRE_DATE:      employee.HIRE_DATE ? new Date(employee.HIRE_DATE) : null,
        JOB_ID:         employee.JOB_ID,
        SALARY:         employee.SALARY,
        COMMISSION_PCT: employee.COMMISSION_PCT,
        MANAGER_ID:     employee.MANAGER_ID,
        DEPARTMENT_ID:  employee.DEPARTMENT_ID
      });
      this.idOriginal = employee.EMPLOYEE_ID;
    }
  }

  onSubmit() {
    if (this.employeeForm.invalid) {
      this.employeeForm.markAllAsTouched();
      return;
    }

    const formValue = this.employeeForm.getRawValue();

    // Converte objeto Date para string YYYY-MM-DD se necessário
    if (formValue.HIRE_DATE instanceof Date) {
      formValue.HIRE_DATE = formValue.HIRE_DATE.toISOString().split('T')[0];
    }

    if (this.config.data?.type === 'edit') {
      this.ref.close({ ...formValue, EMPLOYEE_ID_ORIGINAL: this.idOriginal });
    } else {
      this.employeeService.createEmployee(formValue).subscribe({
        next: (res) => this.ref.close(res),
        error: (err) => {
          this.messageService.add({
            severity: 'error',
            summary: 'Erro',
            detail: 'Não foi possível cadastrar o funcionário.'
          });
        }
      });
    }
  }



}
