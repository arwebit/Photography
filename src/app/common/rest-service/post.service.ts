import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable, take } from 'rxjs';
import { GlobalVariable } from '../global/variable';

@Injectable({
  providedIn: 'root'
})
export class PostService {

  constructor(private http: HttpClient) { }

  POST_API: string = '/api/blogs';

  addPost(data: any): Observable<any> {
    return this.http.post(GlobalVariable.BASE_API_URL + this.POST_API, data).pipe(take(1));
  }
  updatePost(postId: number, data: any): Observable<any> {
    return this.http.post(GlobalVariable.BASE_API_URL + this.POST_API + "/" + postId, data).pipe(take(1));
  }
  updatePostStatus(postId: number, data: any): Observable<any> {
    return this.http.put(GlobalVariable.BASE_API_URL + this.POST_API + "/" + postId, data).pipe(take(1));
  }
  listPost(): Observable<any> {
    return this.http.get(GlobalVariable.BASE_API_URL + this.POST_API).pipe(take(1));
  }
  listPostById(postId: number): Observable<any> {
    return this.http.get(GlobalVariable.BASE_API_URL + this.POST_API + "/" + postId).pipe(take(1));
  }
}
