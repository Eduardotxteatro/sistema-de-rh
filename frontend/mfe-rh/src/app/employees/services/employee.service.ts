import { inject, Injectable } from '@angular/core';
import { HttpClient, HttpParams, HttpHeaders } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})

export class EmployeeService {

  headers = HttpHeaders;

  //private http = inject(HttpClient);
  private apiUrl = 'http://localhost:8765';
  constructor(private http: HttpClient) { }

  getEmployees(params: {
    offset: number;
    limit: number;
    order?: string;
    direction: string;
    filters?: { field?: string | null; value?: unknown };
  }): Observable<any> {
    let query = new HttpParams()
      .set('offset', params.offset)
      .set('limit', params.limit)
      .set('direction', params.direction);

    if (params.order) {
      query = query.set('order', params.order);
    }
    if (params.filters?.field) {
      query = query.set('filters', JSON.stringify(params.filters));
    }

    return this.http.get(`${this.apiUrl}/api/employees/get-all-employees.json`, { params: query });
  }

  createEmployee(employee: any){
    
    const endpoint = `${this.apiUrl}/api/employees/create-employee.json`;
    return this.http.post<any>(endpoint, employee);

  }

  deleteEmployee(employeeId: number): Observable<any> {
      const url = `/api/employees/${employeeId}`;
      return this.http.delete<any>(url);
  }
}
