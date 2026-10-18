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
      FIRST_NAME: ["", Validators.required],
      LAST_NAME: ["", Validators.required],
      EMAIL: ["", Validators.required],
      PHONE_NUMBER: ["", Validators.required],
      HIRE_DATE: [Date, Validators.required],
      JOB_ID: ["", Validators.required],
      SALARY: [Number],
      COMMISSION_PCT: [Number],
      MANAGER_ID: [Number],
      DEPARTMENT_ID: [Number]

    })
  }
  setEmployeeData(data: any) {
    const employee = data?.employeeData;

    if(data?.type === 'edit' && employee){
      this.employeeForm.patchValue({
        EMPLOYEE_ID:    employee.EMPLOYEE_ID,
        FIRST_NAME:     employee.FIRST_NAME,
        LAST_NAME:      employee.LAST_NAME,
        EMAIL:          employee.EMAIL,
        PHONE_NUMBER:   employee.EMAIL,
        HIRE_DATE:      employee.HIRE_DATE,
        JOB_ID:         employee.JOB_ID,
        SALARY:         employee.SALARY,
        COMMISSION_PCT: employee.COMMISSION_PCT,
        MANAGER_ID:     employee.MANAGER_ID,
        DEPARTMENT_ID:  employee.DEPARTMENT_ID
      });
      this.idOriginal = employee.EMPLOYEE_ID;

    }

  }

  onSubmit(){
    const formValue = this.employeeForm.getRawValue();

    if(this.config.data.type === 'edit'){
      this.ref.close({...formValue, EMPLOYEE_ID_ORIGINAL: this.idOriginal})
    }else{
      this.employeeService.createEmplyee(formValue).subscribe(res => this.ref.close(res));
    }
  }



}
