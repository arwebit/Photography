import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterModule } from '@angular/router';

import { TopComponent } from './top/top.component';
import { SidebarComponent } from './sidebar/sidebar.component';
import { BottomComponent } from './bottom/bottom.component';



@NgModule({
  declarations: [
    TopComponent,
    SidebarComponent,
    BottomComponent
  ],
  imports: [
    CommonModule,
    RouterModule
  ],
  exports: [
    TopComponent,
    SidebarComponent,
    BottomComponent
  ]
})
export class LayoutsModule { }
