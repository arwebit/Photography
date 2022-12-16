import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { AddComponent } from './add/add.component';
import { EditComponent } from './edit/edit.component';
import { ViewComponent } from './view/view.component';

const routes: Routes = [
  {
    path: '',
    redirectTo: '/view',
    pathMatch: 'full'
  },
  {
    path: 'add',
    component: AddComponent,
    data: { title: 'Photography admin : Add category' }
  },
  {
    path: 'view',
    component: ViewComponent,
    data: { title: 'Photography admin : View categories' }
  },
  {
    path: 'edit/:categoryID',
    component: EditComponent,
    data: { title: 'Photography admin : Edit category' }
  },
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class CategoryRoutingModule { }
