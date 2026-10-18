import { ComponentFixture, TestBed } from '@angular/core/testing';

import { EmployeesFormDialogComponent } from './employees-form-dialog.component';

describe('EmployeesFormDialogComponent', () => {
  let component: EmployeesFormDialogComponent;
  let fixture: ComponentFixture<EmployeesFormDialogComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ EmployeesFormDialogComponent ]
    })
    .compileComponents();

    fixture = TestBed.createComponent(EmployeesFormDialogComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
