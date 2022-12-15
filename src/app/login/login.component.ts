import { HttpErrorResponse } from '@angular/common/http';
import { Component } from '@angular/core';
import { FormGroup, FormControl } from '@angular/forms';
import { Router } from '@angular/router';
import { LoginServiceService } from '../common/rest-service/login-service.service';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css']
})
export class LoginComponent {
  constructor(private loginService: LoginServiceService, private router: Router) {
    localStorage.clear();
  }
  myForm!: FormGroup;
  User: string = "";
  AdminName: string = "";
  statusCode: number = 0;
  userNameErr: string | null = null;
  passwordErr: string | null = null;
  loginErr: string | null = null;

  ngOnInit(): void {
    this.myForm = new FormGroup({
      Username: new FormControl(''),
      Password: new FormControl(''),
    });
  }
  login() {
    this.loginService.login(this.myForm.value).subscribe(
      (result) => {
        this.statusCode = result.statusCode;

        if (this.statusCode == 200) {
          localStorage.setItem('userToken', result.data.Token);
          for (let userDetails of result.data.User_details) {
            this.User = userDetails.username;
            this.AdminName = userDetails.full_name;
          }
          localStorage.setItem('userName', this.User);
          localStorage.setItem('fullName', this.AdminName);
          this.router.navigate(['/home']);
        } else {
          this.userNameErr = result.error.Username;
          this.passwordErr = result.error.Password;
          this.loginErr = result.error.Login;
        }
      }
    ), (err: HttpErrorResponse): void => {
      alert(err);
    };
  }
}
