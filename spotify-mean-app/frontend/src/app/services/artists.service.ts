import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class ArtistsService {
  private API_SERVER: string = 'http://localhost:3500/api/artists'
  constructor(
    private httpClient: HttpClient
  ) { }

  public getArtists = (): Observable<any> => {
    return this.httpClient.get(`${ this.API_SERVER }`, {});
  }

  public getPlaylists = (): Observable<any> => {
    return this.httpClient.get(``, {});
  }

}
