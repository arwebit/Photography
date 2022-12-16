import { Component } from '@angular/core';
import { Router } from '@angular/router';
import { PostService } from 'src/app/common/rest-service/post.service';

@Component({
  selector: 'app-view-post',
  templateUrl: './view-post.component.html',
  styleUrls: ['./view-post.component.css']
})
export class ViewPostComponent {
  pageName: string = "View post";
  postList: any = [];
  isData: boolean = false;
  errorMessage: string = "";
  statusCode: number = 0;
  postStatus: number = 0;

  constructor(private postService: PostService, private router: Router) { }

  ngOnInit(): void {
    this.init();
  }
  init() {
    this.postService.listPost().subscribe(result => {
      this.statusCode = result.statusCode;
      if (this.statusCode == 200) {
        this.isData = true;
        this.postList = result.data;
      } else {
        this.errorMessage = "No records found";
      }
    });
  }
  statusChange(postID: number, status: number) {
    this.postStatus = status == 0 ? 1 : 0;
    const data = { Status: this.postStatus };
    this.postService.updatePostStatus(postID, data).subscribe(result => {
      this.statusCode = result.statusCode;
      if (this.statusCode == 201) {
        this.init();
        this.router.navigate(["/post/view"]);
      } else {
        this.errorMessage = result.error.Status;
      }
    });
  }
}
