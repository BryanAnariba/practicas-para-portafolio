import { Component, OnInit } from '@angular/core';


import { faGift, faCheck, faCamera, faPhone } from '@fortawesome/free-solid-svg-icons';

@Component({
  selector: 'app-home',
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.css']
})
export class HomeComponent implements OnInit {
  public faGift = faGift;
  public faCheck = faCheck;
  public faCamera = faCamera;
  public faPhone = faPhone;
  constructor() { }

  ngOnInit(): void {
  }

}
