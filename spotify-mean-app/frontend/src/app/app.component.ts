import { Component } from '@angular/core';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.scss']
})
export class AppComponent {
  title = 'frontend';
  vistaSeleccionada: string = '';

  // Capturando evento producido en sidebar.component.ts para ver albumes de un artista seleccionado
  verArtista (artistData: any) {
    this.vistaSeleccionada = 'artistas';
    console.log(artistData);
  }

  // Capturando evento producido en sidebar.component.ts para ver playlists de un usuario seleccionado
  verPlaylist (playlistData: any) {
    this.vistaSeleccionada = 'playlists';
    console.log(playlistData);
  }
}

