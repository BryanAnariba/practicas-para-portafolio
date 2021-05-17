import { Component, EventEmitter, OnInit, Output } from '@angular/core';

import { faMusic, faPlay, faPlus } from '@fortawesome/free-solid-svg-icons';
@Component({
  selector: 'app-siderbar',
  templateUrl: './siderbar.component.html',
  styleUrls: ['./siderbar.component.scss']
})
export class SiderbarComponent implements OnInit {
  constructor() { }

  ngOnInit(): void {
  }

  faMusic = faMusic;
  faPlay = faPlay;
  faPlus = faPlus;

  // Decorador para comunicarme con el componente albumes para ver artistas
  @Output() onViewAlbumes = new EventEmitter();
  verArtista (id: string | number) {
    this.onViewAlbumes.emit({ artistId: id });
  }

  // Decorador para comunicarme con el componente playlist para ver las playlists del usuario seleccionado
  @Output() onViewPlaylists = new EventEmitter();
  verPlaylist (id: string | number) {
    this.onViewPlaylists.emit({ playlistId: id })
  }

}
