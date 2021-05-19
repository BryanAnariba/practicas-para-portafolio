import { Component, OnInit } from '@angular/core';


import { ToastrService } from 'ngx-toastr';
import { faMusic, faPlay, faPlus } from '@fortawesome/free-solid-svg-icons';
import { IPlaylists } from 'src/app/interfaces/iplaylists';

@Component({
  selector: 'app-playlists',
  templateUrl: './playlists.component.html',
  styleUrls: ['./playlists.component.scss']
})
export class PlaylistsComponent implements OnInit {
  faMusic = faMusic;
  faPlay = faPlay;
  faPlus = faPlus;
  playlist: any = {};
  constructor(private toastr: ToastrService) { }

  ngOnInit(): void {

  }

  public getPlaylist = (playlist: IPlaylists) => {
    this.playlist = playlist;
    console.log(this.playlist)
  }
}
