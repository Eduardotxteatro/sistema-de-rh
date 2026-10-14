import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { HttpClientModule } from '@angular/common/http';


import { TableModule } from 'primeng/table';
import { ButtonModule } from 'primeng/button';
import { ConfirmDialogModule } from 'primeng/confirmdialog';
import { ToastModule } from 'primeng/toast';
import { MenuModule } from 'primeng/menu';

import { ConfirmationService, MessageService } from 'primeng/api';

import { SharedModule } from '../shared/shared.module';
import { EmployeesRoutingModule } from './employees-routing.module';
import { EmployeesListComponent } from './employees-list/employees-list.component';


@NgModule({
  declarations: [
    EmployeesListComponent
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
    MenuModule
  ],
  providers: [
    ConfirmationService,
    MessageService
  ]
})
export class EmployeesModule { }
