import { Component } from '@angular/core';
import { Title } from '@angular/platform-browser';
import { Router, ActivatedRoute, NavigationEnd } from '@angular/router';
import { filter } from 'rxjs';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css']
})
export class AppComponent {
  constructor(private router: Router, private activatedRoute: ActivatedRoute, private titleService: Title) { }

  ngOnInit() {

    this.router.events.pipe(
      filter(event => event instanceof NavigationEnd),
    )
      .subscribe(() => {

        var rt = this.getChild({ activatedRoute: this.activatedRoute })

        rt.data.subscribe((data: { title: string; }) => {
          console.log(data);
          this.titleService.setTitle(data.title)
        })
      })

  }

  getChild({ activatedRoute }: { activatedRoute: ActivatedRoute; }): any {
    if (activatedRoute.firstChild) {
      return this.getChild({ activatedRoute: activatedRoute.firstChild });
    } else {
      return activatedRoute;
    }

  }
}
