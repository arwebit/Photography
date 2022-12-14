import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { CategoryRoutingModule } from './category-routing.module';
import { CategoryComponent } from './category.component';
import { LayoutsModule } from '../layouts/layouts.module';
import { AddComponent } from './add/add.component';
import { ViewComponent } from './view/view.component';


@NgModule({
  declarations: [
    CategoryComponent,
    AddComponent,
    ViewComponent
  ],
  imports: [
    CommonModule,
    CategoryRoutingModule,
    LayoutsModule
  ]
})
export class CategoryModule { }
