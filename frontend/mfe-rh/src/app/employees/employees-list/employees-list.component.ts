import { Component, OnInit } from '@angular/core';
import { ConfirmationService, LazyLoadEvent, MenuItem, MessageService } from 'primeng/api';
import { EmployeeService } from '../services/employee.service';
import { tap } from 'rxjs/internal/operators/tap';
import { catchError } from 'rxjs/internal/operators/catchError';
import { DialogService, DynamicDialogRef } from 'primeng/dynamicdialog';
import { of } from 'rxjs';
import { EmployeesFormDialogComponent } from '../employees-form-dialog/employees-form-dialog.component';

@Component({
  selector: 'app-employees-list',
  templateUrl: './employees-list.component.html',
  styleUrls: ['./employees-list.component.scss'],
  providers: [ConfirmationService, MessageService]
})
export class EmployeesListComponent implements OnInit {

  dataEmployees: any[] = [];
  typeColumns: any;
  rows: number = 10;
  totalRecords: number = 0;
  visibleItems: MenuItem[] = [];
  isLoading: boolean = false;
  lastLazy: any;
  modalRef!: DynamicDialogRef;

  constructor(
    private dialogService: DialogService,
    private employeesService: EmployeeService,
    private confirmationService: ConfirmationService,
    private messageService: MessageService,
  ) { }

  ngOnInit(): void {
    this.setTable();
    this.setVisibleItems();
  }

  setTable(){
    this.typeColumns = [
      { field: 'EMPLOYEE_ID', header: 'ID',filter: true, width: '80px' },
      { field: 'FIRST_NAME', header: 'Nome',filter: true, width: '150px' },
      { field: 'LAST_NAME', header: 'Sobrenome',filter: true, width: '150px' },
      { field: 'EMAIL', header: 'E-mail',filter: true, width: '200px' },
      { field: 'PHONE_NUMBER', header: 'Telefone',filter: true, width: '150px' },
      { field: 'HIRE_DATE', header: 'Data Admissão',filter: true, width: '140px' }
    ];
  }

  onLazyLoad(event: LazyLoadEvent) {
    this.isLoading = true;
    this.lastLazy = event;
    this.getEmployeesList(event);
  }

  onFilterChange(event: any) {
    this.lastLazy = event;
    this.getEmployeesList(event);
  }
  getEmployeesList(event: any) {
    this.isLoading = true;

    const first = event?.first || 0;
    const rows = event?.rows || 10;

    const params = {
      offset: first,
      limit: rows,
      order: event?.sortField,
      filters: {
        field: event.filters ? event.field : null,
        value: event.value,
      },
      direction: event?.sortOrder === 1 ? 'asc' : 'desc',
    };

    this.employeesService.getEmployees(params)
      .pipe(
        tap((res)=> {
          this.dataEmployees = res.payload?.data || [];
          this.totalRecords = res?.payload?.totalRecords || 0;
        }),
        catchError((err) => {
          this.messageService.add({ 
            severity: 'error', 
            summary: 'Error', 
            detail: err.message || 'Failed to load employees data.',
            life: 5000
          });
          return of(null);
        })

      )
      .subscribe(() => {
        this.isLoading = false;
      });
  }

  createEmployee(){
    this.modalRef = this.dialogService.open(EmployeesFormDialogComponent, {
      data: { type: "add"},
      header: "Adicionar novo funcionário",
      width: "50%",
      height: "60%"
    });
    this.modalRef.onClose.subscribe((result: any)=>{
      if(result && (result.status === '0000' || result.status === 200 || result === true)){
        this.messageService.add({
          severity: 'success',
          summary: 'Novo funcionário',
          detail: 'Funcionário adicionado com sucesso!'
        });
        this.getEmployeesList(this.lastLazy);
      }
    })
  }

  options(): any{
    return{
      icon: "pi pi-plus",
      actionType: "button",
      callback: ()=>this.createEmployee(),
      visible: true,
    };
  }

  editEmployee(selectedItem: any){
    console.log('Editar:', selectedItem);
  }

  deleteEmployee(selectedItem: any){
    console.log('Apagar:', selectedItem);
    this.confirmationService.confirm({
      message: `Tem certeza que deseja apagar o funcionário ${selectedItem.EMPLOYEE_ID}?`,
      header: 'Confirmação',
      icon: 'pi pi-exclamation-triangle',
      accept: () => {
        this.employeesService.deleteEmployee(selectedItem.EMPLOYEE_ID).subscribe({
          next: (res) => {
            this.messageService.add({
              severity: 'success',
              summary: 'Sucesso',
              detail: 'Funcionário apagado com sucesso.',
              life: 3000
            });
            this.getEmployeesList(this.lastLazy);
            console.log('deleteEmployee', selectedItem);
          },
          error: (err) => {
            this.messageService.add({
              severity: 'error',
              summary: 'Erro',
              detail: err.message || 'Falha ao apagar funcionário.',
              life: 5000
            });
          }
        });
          
      }
    });
  }

  setVisibleItems(selectedItem:any = ""){
    this.visibleItems = [
      {
        label: "Modificar",
        icon: "pi pi-pencil",
        command: () => this.editEmployee(selectedItem),
        styleClass: "menu-icon-warning",
      },
      {
        label: "Apagar",
        icon: "pi pi-trash",
        command: () => this.deleteEmployee(selectedItem),
        styleClass: "menu-icon-danger",
      },
    ];
  }


}
