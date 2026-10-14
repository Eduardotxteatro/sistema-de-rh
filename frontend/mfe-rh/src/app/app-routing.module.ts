import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';

const routes: Routes = [
  {
    path: 'employees',   
    loadChildren: () => import('./employees/employees-routing.module').then(m => m.EmployeesRoutingModule)
  },
  { path: 'countries',
    loadChildren: () => import('./countries/countries-routing.module').then(m => m.CountriesRoutingModule)
  },
  { path: '', redirectTo: 'employees', pathMatch: 'full' },
  { path: '**', redirectTo: 'employees', pathMatch: 'full' }
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
