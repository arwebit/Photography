import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { PostRoutingModule } from './post-routing.module';
import { PostComponent } from './post.component';
import { LayoutsModule } from '../layouts/layouts.module';
import { AddPostComponent } from './add-post/add-post.component';
import { ViewPostComponent } from './view-post/view-post.component';
import { ReactiveFormsModule } from '@angular/forms';
import { EditPostComponent } from './edit-post/edit-post.component';


@NgModule({
  declarations: [
    PostComponent,
    AddPostComponent,
    ViewPostComponent,
    EditPostComponent
  ],
  imports: [
    CommonModule,
    PostRoutingModule,
    LayoutsModule,
    ReactiveFormsModule
  ]
})
export class PostModule { }
