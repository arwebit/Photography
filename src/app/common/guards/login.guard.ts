import { Injectable } from '@angular/core';
import { CanActivate, Router } from '@angular/router';
import { LoginServiceService } from '../rest-service/login-service.service';

@Injectable({
  providedIn: 'root'
})
export class LoginGuard implements CanActivate {
  constructor(private loginService: LoginServiceService, private router: Router) { }
  canActivate() {
    if (this.loginService.isLoggedIn()) {
      return true;
    } else {
      this.router.navigate(['']);
      return false;
    }
  }

}
