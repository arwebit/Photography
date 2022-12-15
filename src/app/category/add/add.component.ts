import { HttpErrorResponse } from '@angular/common/http';
import { Component } from '@angular/core';
import { FormGroup, FormControl } from '@angular/forms';
import { CategoryService } from 'src/app/common/rest-service/category.service';

@Component({
  selector: 'app-add',
  templateUrl: './add.component.html',
  styleUrls: ['./add.component.css']
})
export class AddComponent {
  constructor(private categoryService: CategoryService) { }
  pageName: string = "Add category";
  categoryForm!: FormGroup;
  statusCode: number = 0;
  categoryNameErr: string | null = null;
  success: string | null = null;

  ngOnInit(): void {
    this.categoryForm = new FormGroup({
      Category: new FormControl('')
    });
  }
  saveCategory() {
    this.categoryService.addCategory(this.categoryForm.value).subscribe(
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
