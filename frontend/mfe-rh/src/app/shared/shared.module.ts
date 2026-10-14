import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { CardModule } from 'primeng/card';
import { ButtonModule } from 'primeng/button';
import { DividerModule } from 'primeng/divider';
import { PageCardComponent } from './components/page-card/page-card.component';

@NgModule({
  declarations: [
    PageCardComponent
  ],
  imports: [
    CommonModule,
    CardModule,
    ButtonModule,
    DividerModule
  ],
  exports: [
    PageCardComponent,
    CommonModule,
    CardModule,
    ButtonModule,
    DividerModule
  ]
})
export class SharedModule { }
