import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { CategoryRoutingModule } from './category-routing.module';
import { CategoryComponent } from './category.component';
import { LayoutsModule } from '../layouts/layouts.module';
import { AddComponent } from './add/add.component';
import { ViewComponent } from './view/view.component';
import { ReactiveFormsModule } from '@angular/forms';


@NgModule({
  declarations: [
    CategoryComponent,
    AddComponent,
    ViewComponent
  ],
  imports: [
    CommonModule,
    CategoryRoutingModule,
    LayoutsModule,
    ReactiveFormsModule
  ]
})
export class CategoryModule { }
