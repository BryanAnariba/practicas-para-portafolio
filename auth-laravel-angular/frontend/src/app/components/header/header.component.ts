import { Component, OnInit } from '@angular/core';


import { faBars, faFill } from '@fortawesome/free-solid-svg-icons';

@Component({
  selector: 'app-header',
  templateUrl: './header.component.html',
  styleUrls: ['./header.component.css']
})
export class HeaderComponent implements OnInit {
  public faBars = faBars;
  public faFill = faFill;
  constructor() { }

  ngOnInit(): void {
  }

}
