import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable, take, catchError, throwError } from 'rxjs';
import { GlobalVariable } from '../global/variable';

@Injectable({
  providedIn: 'root'
})
export class LoginServiceService {

  constructor(private http: HttpClient) { }

  loggedIn: boolean = false;
  LOGIN_API: string = '/api/admin/login';

  login(data: any): Observable<any> {
    return this.http.post(GlobalVariable.BASE_API_URL + this.LOGIN_API, data)
      .pipe(take(1))
  }

  isLoggedIn() {
    if (localStorage.getItem('userToken') != null && localStorage.getItem('userName') != null) {
      this.loggedIn = true;
    }
    return this.loggedIn;
  }

}
