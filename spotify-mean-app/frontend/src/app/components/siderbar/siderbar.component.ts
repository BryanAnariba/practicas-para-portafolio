import { Component, EventEmitter, OnInit, Output } from '@angular/core';

import { faMusic, faPlay, faPlus } from '@fortawesome/free-solid-svg-icons';
import { ToastrService } from 'ngx-toastr';
import { IArtists } from 'src/app/interfaces/iartists';
import { IPlaylists } from 'src/app/interfaces/iplaylists';
import { ArtistsService } from 'src/app/services/artists.service';
import { UsersService } from 'src/app/services/users.service';
@Component({
  selector: 'app-siderbar',
  templateUrl: './siderbar.component.html',
  styleUrls: ['./siderbar.component.scss']
})
export class SiderbarComponent implements OnInit {
  constructor(
    private artistService: ArtistsService,
    private usersService: UsersService,
    private toastr: ToastrService
    ) { }

  public artists: IArtists[] = [];
  public playlists: IPlaylists[] = [];

  ngOnInit(): void {
    this.getArtists()
  }

  faMusic = faMusic;
  faPlay = faPlay;
  faPlus = faPlus;

  // Decorador para comunicarme con el componente albumes para ver artistas
  @Output() onViewAlbumes = new EventEmitter();
  verArtista (artistData: any) {
    this.onViewAlbumes.emit(artistData);
  }

  // Decorador para comunicarme con el componente playlist para ver las playlists del usuario seleccionado
  @Output() onViewPlaylists = new EventEmitter();
  verPlaylist (playlistData: any) {
    this.onViewPlaylists.emit(playlistData);
  }

  private getArtists = () => {
    this.artistService.getArtists()
    .subscribe(
      (artists) => {
        this.artists = artists.data;
        console.log(this.artists);
      },
      (error: any) => {
        console.log(error);
    });
  }

  public getPlaylists = (userData: any) => {
    this.usersService.getPlaylistByUser( userData.userId )
    .subscribe(
      (playlists: any) => {
        this.playlists = playlists.data.playlists;
        console.log(this.playlists);
      },
      (error: any) => {
        this.toastr.error(error, 'Error');
      }
    );
  }

  public getPlaylist = (playlistId: string) => {
    console.log(playlistId);
  }
}
