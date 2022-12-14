import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { AddPostComponent } from './add-post/add-post.component';
import { ViewPostComponent } from './view-post/view-post.component';

const routes: Routes = [
  {
    path: '',
    redirectTo: '/view',
    pathMatch: 'full'
  },
  {
    path: 'add',
    component: AddPostComponent,
    data: { title: 'Photography admin : Add post' }
  },
  {
    path: 'view',
    component: ViewPostComponent,
    data: { title: 'Photography admin : View posts' }
  },
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class PostRoutingModule { }
