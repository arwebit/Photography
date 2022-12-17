import { HttpErrorResponse } from '@angular/common/http';
import { Component } from '@angular/core';
import { CategoryService } from '../common/rest-service/category.service';
import { PostService } from '../common/rest-service/post.service';

@Component({
  selector: 'app-home',
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.css']
})
export class HomeComponent {
  pageName: string = "Dashboard";
  categoryRecord: number = 0;
  blogRecord: number = 0;
  statusCode: number = 0;

  constructor(private categoryService: CategoryService, private postService: PostService) {
    this.categoryService.listCategory().subscribe((result) => {
      this.statusCode = result.statusCode;
      if (this.statusCode == 200) {
        this.categoryRecord = result.records;
      }
    }), (err: HttpErrorResponse): void => {
      alert(err);
    };

    this.postService.listPost().subscribe((result) => {
      this.statusCode = result.statusCode;
      if (this.statusCode == 200) {
        this.blogRecord = result.records;
      }
    }), (err: HttpErrorResponse): void => {
      alert(err);
    };
  }

  ngOnInit(): void { }
}
