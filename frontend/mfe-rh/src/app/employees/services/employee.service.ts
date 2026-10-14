import { inject, Injectable } from '@angular/core';
import { HttpClient, HttpParams } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})

export class EmployeeService {

  private http = inject(HttpClient);

private apiUrl = 'http://localhost:8765/api/employees/get-all-employees.json';
  constructor() { }

  getEmployees(rawParams?: Record<string, any>): Observable<any> {
    let params = new HttpParams();

    if (rawParams) {
      Object.keys(rawParams).forEach((key) => {
        const val = rawParams[key];

        // Ignora valores nulos ou undefined
        if (val !== undefined && val !== null) {
          // Se for objeto/array (como filters), serializa para JSON
          if (typeof val === 'object') {
            params = params.set(key, JSON.stringify(val));
          } else {
            params = params.set(key, String(val));
          }
        }
      });
    }

    return this.http.get<any>(this.apiUrl, { params });
  }

  deleteEmployee(employeeId: number): Observable<any> {
      const url = `/api/employees/${employeeId}`;
      return this.http.delete<any>(url);
  }
}
