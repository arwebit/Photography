import { Component } from '@angular/core';
import { Router } from '@angular/router';
import { CategoryService } from './../../common/rest-service/category.service';

@Component({
  selector: 'app-view',
  templateUrl: './view.component.html',
  styleUrls: ['./view.component.css']
})
export class ViewComponent {
  pageName: string = "View category";
  categoryList: any = [];
  isData: boolean = false;
  errorMessage: string = "";
  statusCode: number = 0;
  categoryStatus: number = 0;

  constructor(private categoryService: CategoryService, private router: Router) { }

  ngOnInit(): void {
    this.init();
  }
  init() {
    this.categoryService.listCategory().subscribe(result => {
      this.statusCode = result.statusCode;
      if (this.statusCode == 200) {
        this.isData = true;
        this.categoryList = result.data;
      } else {
        this.errorMessage = "No records found";
      }
    });
  }
  statusChange(categoryID: number, status: number) {
    this.categoryStatus = status == 0 ? 1 : 0;
    const data = { Status: this.categoryStatus };
    this.categoryService.updateCategoryStatus(categoryID, data).subscribe(result => {
      this.statusCode = result.statusCode;
      if (this.statusCode == 201) {
        this.init();
        this.router.navigate(["/category/view"]);
      } else {
        this.errorMessage = result.error.Status;
      }
    });
  }

}
