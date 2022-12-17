import { HttpErrorResponse } from '@angular/common/http';
import { Component } from '@angular/core';
import { FormGroup, FormBuilder, FormControl } from '@angular/forms';
import { ActivatedRoute } from '@angular/router';
import { CategoryService } from 'src/app/common/rest-service/category.service';
import { PostService } from 'src/app/common/rest-service/post.service';

@Component({
  selector: 'app-edit-post',
  templateUrl: './edit-post.component.html',
  styleUrls: ['./edit-post.component.css']
})
export class EditPostComponent {
  pageName: string = "Edit post";
  postEditForm!: FormGroup;
  postID: number = 0;
  postImage: string = "";
  categoryList: any = [];
  blogList: any = [];
  statusCode: number = 0;
  categoryErr: string | null = null;
  titleErr: string | null = null;
  descriptionErr: string | null = null;
  blogImageErr: string | null = null;
  success: string | null = null;
  im: any = {};

  constructor(private activatedRoute: ActivatedRoute, private fb: FormBuilder, private postService: PostService, private categoryService: CategoryService) {
    this.activatedRoute.params.subscribe((params) => {
      this.postID = params['postID'];
    });
    this.categoryService.listCategory().subscribe(result => {
      this.statusCode = result.statusCode;
      if (this.statusCode == 200) {
        this.categoryList = result.data;
      }
    });
    this.init();
    this.postEditForm = this.fb.group({
      BlogCategory: new FormControl(''),
      Title: new FormControl(''),
      HTitle: new FormControl(''),
      Description: new FormControl('')
    });
  }

  ngOnInit(): void { }
  init() {
    this.postService.listPostById(this.postID).subscribe(
      (result) => {
        this.statusCode = result.statusCode;
        if (this.statusCode == 200) {
          this.blogList = result.data;
          for (let blogData of this.blogList) {
            this.postImage = "data:" + blogData.photo_mime_type + ";base64," + blogData.photo_encrypted_str;
            this.postEditForm = new FormGroup({
              BlogCategory: new FormControl(blogData.blog_category_id),
              Title: new FormControl(blogData.blog_title),
              HTitle: new FormControl(blogData.blog_title),
              Description: new FormControl(blogData.blog_descr)
            });
          }
        }
      }
    ), (err: HttpErrorResponse): void => {
      alert(err);
    };
  }
  uploadPFile(event: any) {
    const file = event.target.files ? event.target.files[0] : "";
    this.im = { Image: file };
  }
  editPost() {
    const datas = {
      BlogCategory: this.postEditForm.value.BlogCategory,
      Title: this.postEditForm.value.Title,
      HTitle: this.postEditForm.value.HTitle,
      Description: this.postEditForm.value.Description,
      Login_User: localStorage.getItem("userName")
    }
    var formData: any = new FormData();
    formData.append("requestData", JSON.stringify(datas));
    formData.append("blogPic", this.im.Image);
    this.postService.updatePost(this.postID, formData).subscribe(
      (result) => {
        this.statusCode = result.statusCode;
        if (this.statusCode == 201) {
          this.init();
          this.success = result.success;
          this.categoryErr = "";
          this.titleErr = "";
          this.descriptionErr = "";
          this.blogImageErr = "";
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
