import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable, take } from 'rxjs';
import { GlobalVariable } from '../global/variable';
import { Category } from '../models/Category.model';

@Injectable({
  providedIn: 'root'
})
export class CategoryService {

  constructor(private http: HttpClient) { }

  CATEGORY_API: string = '/api/categorys';

  addCategory(data: Category): Observable<any> {
    return this.http.post(GlobalVariable.BASE_API_URL + this.CATEGORY_API, data).pipe(take(1));
  }
  updateCategory(categoryId: number, data: any): Observable<any> {
    return this.http.post(GlobalVariable.BASE_API_URL + this.CATEGORY_API + "/" + categoryId, data).pipe(take(1));
  }
  updateCategoryStatus(categoryId: number, data: any): Observable<any> {
    return this.http.put(GlobalVariable.BASE_API_URL + this.CATEGORY_API + "/" + categoryId, data).pipe(take(1));
  }
  listCategory(): Observable<any> {
    return this.http.get(GlobalVariable.BASE_API_URL + this.CATEGORY_API).pipe(take(1));
  }
  listCategoryById(categoryId: number): Observable<any> {
    return this.http.get(GlobalVariable.BASE_API_URL + this.CATEGORY_API + "/" + categoryId).pipe(take(1));
  }
}
