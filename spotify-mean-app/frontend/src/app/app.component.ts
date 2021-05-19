import { Component, ViewChild } from '@angular/core';
import { AlbumesComponent } from './components/albumes/albumes.component';
import { HeaderComponent } from './components/header/header.component';
import { PlaylistsComponent } from './components/playlists/playlists.component';

import { SiderbarComponent } from './components/siderbar/siderbar.component';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.scss']
})
export class AppComponent {
  @ViewChild('sidebar') sidebarComponent?: SiderbarComponent;
  @ViewChild('playlist') playlistComponent?: PlaylistsComponent;
  @ViewChild('albumes') albumesComponent?: AlbumesComponent;
  @ViewChild('header') headerComponent?: HeaderComponent;

  title = 'frontend';
  vistaSeleccionada: string = '';

  // Capturando evento producido en sidebar.component.ts para ver albumes de un artista seleccionado
  public verArtista (artistData: any) {
    this.vistaSeleccionada = 'artistas';
    console.log(artistData);
    this.albumesComponent?.getAlbumes(artistData);
  }

  // Capturando evento producido en sidebar.component.ts para ver playlists de un usuario seleccionado
  public verPlaylist (playlistData: any) {
    this.vistaSeleccionada = 'playlists';
    this.playlistComponent?.getPlaylist(playlistData);

    console.log(playlistData);
  }

  // Capturando evento producido en header.component.ts para obtener el usuario y enviarlo al componente sidebar
  public getUserPlaylists = (userData: any) => {
    this.albumesComponent!.userSelected = userData.userId;
    console.log('Datos recibidos provenientes de el componente header.component.ts', userData);
    this.sidebarComponent?.getPlaylists(userData);
  }


  refreshUserPlaylists = () => {
    this.headerComponent?.getUserPlaylists();
  }
}

