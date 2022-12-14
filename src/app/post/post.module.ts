import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { PostRoutingModule } from './post-routing.module';
import { PostComponent } from './post.component';
import { LayoutsModule } from '../layouts/layouts.module';
import { AddPostComponent } from './add-post/add-post.component';
import { ViewPostComponent } from './view-post/view-post.component';


@NgModule({
  declarations: [
    PostComponent,
    AddPostComponent,
    ViewPostComponent
  ],
  imports: [
    CommonModule,
    PostRoutingModule,
    LayoutsModule
  ]
})
export class PostModule { }
