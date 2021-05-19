import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class UsersService {
  private API_SERVER = 'http://localhost:3500/api/users';
  constructor(private httpClient: HttpClient) { }

  getUsers = (): Observable<any> => {
    return this.httpClient.get(`${ this.API_SERVER }`, {});
  }

  getPlaylistByUser = ( userId: string ): Observable<any> => {
    return this.httpClient.get(`${ this.API_SERVER }/${ userId }/playlists`, {});
  }

  saveSongInPlaylist = (
    songData: any,
    album: string,
    userId: string,
    playlistId: string): Observable<any> => {
    return this.httpClient.post(`${ this.API_SERVER }/${ userId }/playlist/${ playlistId }/song`, {
      nombreCancion: songData.nombreCancion,
      artista: songData.artista,
      album: album
    });
  }

  savePlaylist = (playlistData: any, userId: string): Observable<any> => {
    return this.httpClient.post(`${ this.API_SERVER }/${ userId }/playlists`, {
      tituloPlayList: playlistData.tituloPlayList
    });
  }
}
