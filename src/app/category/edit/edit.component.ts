import { HttpErrorResponse } from '@angular/common/http';
import { Component } from '@angular/core';
import { FormGroup, FormControl } from '@angular/forms';
import { ActivatedRoute } from '@angular/router';
import { CategoryService } from 'src/app/common/rest-service/category.service';

@Component({
  selector: 'app-edit',
  templateUrl: './edit.component.html',
  styleUrls: ['./edit.component.css']
})
export class EditComponent {
  pageName: string = "Edit category";
  categoryForm!: FormGroup;
  categoryID: number = 0;
  statusCode: number = 0;
  categoryList: any = [];
  categoryNameErr: string | null = null;
  success: string | null = null;

  constructor(private categoryService: CategoryService, private activatedRoute: ActivatedRoute) {
    this.activatedRoute.params.subscribe((params) => {
      this.categoryID = params['categoryID'];
    });
    this.categoryService.listCategoryById(this.categoryID).subscribe(
      (result) => {
        this.statusCode = result.statusCode;
        if (this.statusCode == 200) {
          this.categoryList = result.data;
          for (let categoryData of this.categoryList) {
            this.categoryForm = new FormGroup({
              Category: new FormControl(categoryData.category_name),
              HCategory: new FormControl(categoryData.category_name)
            });
          }
        }
      }), (err: HttpErrorResponse): void => {
        alert(err);
      };
  }

  ngOnInit(): void {
    this.categoryForm = new FormGroup({
      Category: new FormControl('')
    });
  }
  saveCategory() {
    this.categoryService.updateCategory(this.categoryID, this.categoryForm.value).subscribe(
      (result) => {
        this.statusCode = result.statusCode;
        if (this.statusCode == 201) {
          this.success = result.success;
          this.categoryNameErr = "";
        } else {
          this.success = "";
          this.categoryNameErr = result.error.Category;
        }
      }
    ), (err: HttpErrorResponse): void => {
      alert(err);
    };
  }
}
