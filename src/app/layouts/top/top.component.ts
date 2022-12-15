import { Component } from '@angular/core';
import { Router } from '@angular/router';

@Component({
  selector: 'app-top',
  templateUrl: './top.component.html',
  styleUrls: ['./top.component.css']
})
export class TopComponent {
  constructor(private router: Router) { }
  FullName: string | null = "";
  logout() {
    this.router.navigate(['']);
    localStorage.removeItem("userName");
    localStorage.removeItem("userToken");
    localStorage.removeItem("fullName");
  }

  ngOnInit(): void {
    this.FullName = localStorage.getItem("fullName");
  }
}
