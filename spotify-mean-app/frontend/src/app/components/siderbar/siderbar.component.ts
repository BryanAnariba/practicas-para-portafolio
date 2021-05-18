import { Component, EventEmitter, OnInit, Output } from '@angular/core';

import { faMusic, faPlay, faPlus } from '@fortawesome/free-solid-svg-icons';
import { IArtists } from 'src/app/interfaces/iartists';
import { ArtistsService } from 'src/app/services/artists.service';
@Component({
  selector: 'app-siderbar',
  templateUrl: './siderbar.component.html',
  styleUrls: ['./siderbar.component.scss']
})
export class SiderbarComponent implements OnInit {
  constructor(private artistService: ArtistsService) { }

  public artists: IArtists[] = [];
  ngOnInit(): void {
    this.getArtists()
  }

  faMusic = faMusic;
  faPlay = faPlay;
  faPlus = faPlus;

  // Decorador para comunicarme con el componente albumes para ver artistas
  @Output() onViewAlbumes = new EventEmitter();
  verArtista (artist: any) {
    this.onViewAlbumes.emit({ artist: artist});
  }

  // Decorador para comunicarme con el componente playlist para ver las playlists del usuario seleccionado
  @Output() onViewPlaylists = new EventEmitter();
  verPlaylist (id: string | number) {
    this.onViewPlaylists.emit({ playlistId: id })
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

  private getPlaylists = () => {

  }
}
