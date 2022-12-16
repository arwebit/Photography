import { HttpErrorResponse } from '@angular/common/http';
import { Component } from '@angular/core';
import { FormGroup, FormControl, FormBuilder } from '@angular/forms';
import { Router } from '@angular/router';
import { CategoryService } from 'src/app/common/rest-service/category.service';
import { PostService } from 'src/app/common/rest-service/post.service';

@Component({
  selector: 'app-add-post',
  templateUrl: './add-post.component.html',
  styleUrls: ['./add-post.component.css']
})
export class AddPostComponent {
  pageName: string = "Add post";
  postForm!: FormGroup;
  categoryList: any = [];
  statusCode: number = 0;
  categoryErr: string | null = null;
  titleErr: string | null = null;
  descriptionErr: string | null = null;
  blogImageErr: string | null = null;
  success: string | null = null;
  formData: any = new FormData();

  constructor(private fb: FormBuilder, private postService: PostService, private categoryService: CategoryService) {
    this.categoryService.listCategory().subscribe(result => {
      this.statusCode = result.statusCode;
      if (this.statusCode == 200) {
        this.categoryList = result.data;
      }
    });
  }

  ngOnInit(): void {
    this.postForm = this.fb.group({
      BlogCategory: new FormControl(''),
      Title: new FormControl(''),
      Description: new FormControl(''),
      Image: [null]
    });
  }
  uploadFile(event: any) {
    const file = event.target.files ? event.target.files[0] : "";
    this.postForm.patchValue({
      Image: file
    });
  }
  savePost() {
    const datas = {
      BlogCategory: this.postForm.value.BlogCategory,
      Title: this.postForm.value.Title,
      Description: this.postForm.value.Description,
      Login_User: localStorage.getItem("userName")
    }
    this.formData.append("requestData", JSON.stringify(datas));
    this.formData.append("blogPic", this.postForm.value.Image);

    this.postService.addPost(this.formData).subscribe(
      (result) => {
        this.statusCode = result.statusCode;
        if (this.statusCode == 201) {
          this.success = result.success;
          this.categoryErr = "";
          this.titleErr = "";
          this.descriptionErr = "";
          this.blogImageErr = "";
          this.postForm.reset();
        } else {
          this.success = "";
          this.categoryErr = result.error.BlogCategory;
          this.titleErr = result.error.Title;
          this.descriptionErr = result.error.Description;
          this.blogImageErr = result.error.Photo;
        }
      }
    ), (err: HttpErrorResponse): void => {
      alert(err);
    };
  }
}
