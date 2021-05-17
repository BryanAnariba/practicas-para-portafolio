import { Component, OnInit } from '@angular/core';


import { faMusic, faPlay, faPlus } from '@fortawesome/free-solid-svg-icons';

@Component({
  selector: 'app-albumes',
  templateUrl: './albumes.component.html',
  styleUrls: ['./albumes.component.scss']
})
export class AlbumesComponent implements OnInit {

  faMusic = faMusic;
  faPlay = faPlay;
  faPlus = faPlus;
  constructor() { }

  ngOnInit(): void {
  }

}
