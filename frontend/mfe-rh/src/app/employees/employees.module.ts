import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { HttpClientModule } from '@angular/common/http';


import { TableModule } from 'primeng/table';
import { ButtonModule } from 'primeng/button';
import { ConfirmDialogModule } from 'primeng/confirmdialog';
import { ToastModule } from 'primeng/toast';
import { MenuModule } from 'primeng/menu';
import { SharedModule } from '../shared/shared.module';
import { EmployeesRoutingModule } from './employees-routing.module';
import { DialogService, DynamicDialogModule } from 'primeng/dynamicdialog';
import { ReactiveFormsModule } from '@angular/forms';
import { ConfirmationService, MessageService } from 'primeng/api';


import { EmployeesListComponent } from './employees-list/employees-list.component';
import { EmployeesFormDialogComponent } from './employees-form-dialog/employees-form-dialog.component';
import { InputTextModule } from 'primeng/inputtext';


@NgModule({
  declarations: [
    EmployeesListComponent,
    EmployeesFormDialogComponent
  ],
  imports: [
    CommonModule,
    EmployeesRoutingModule,
    HttpClientModule,
    TableModule,
    ButtonModule,
    ConfirmDialogModule,
    ToastModule,
    SharedModule,
    MenuModule,
    DynamicDialogModule,
    ReactiveFormsModule,
    InputTextModule
],
  providers: [
    ConfirmationService,
    MessageService,
    DialogService
  ]
})
export class EmployeesModule { }
