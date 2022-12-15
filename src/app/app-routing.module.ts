import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { LoginGuard } from './common/guards/login.guard';


const routes: Routes = [
  {
    path: '',
    redirectTo: '/login',
    pathMatch: 'full'
  },
  {
    path: 'login',
    loadChildren: () => import('./login/login.module').then(m => m.LoginModule),
    data: { title: 'Photography admin : Login' }
  },
  {
    path: 'home',
    loadChildren: () => import('./home/home.module').then(m => m.HomeModule),
    data: { title: 'Photography admin : Dashboard' },
    canActivate: [LoginGuard]
  },
  {
    path: 'category',
    loadChildren: () => import('./category/category.module').then(m => m.CategoryModule),
    data: { title: 'Photography admin : Category' },
    canActivate: [LoginGuard]
  },
  {
    path: 'post',
    loadChildren: () => import('./post/post.module').then(m => m.PostModule),
    data: { title: 'Photography admin : Post' },
    canActivate: [LoginGuard]
  },

];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
