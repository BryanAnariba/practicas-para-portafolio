import { Component, OnInit } from '@angular/core';


import { ToastrService } from 'ngx-toastr';
import { faMusic, faPlay, faPlus } from '@fortawesome/free-solid-svg-icons';

@Component({
  selector: 'app-playlists',
  templateUrl: './playlists.component.html',
  styleUrls: ['./playlists.component.scss']
})
export class PlaylistsComponent implements OnInit {

  faMusic = faMusic;
  faPlay = faPlay;
  faPlus = faPlus;
  constructor(private toastr: ToastrService) { }

  ngOnInit(): void {

  }

  showSuccess() {
    this.toastr.success('Hello world!', 'Toastr fun!');
  }

}
