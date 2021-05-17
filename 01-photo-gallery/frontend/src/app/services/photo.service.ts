import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { Photo } from '../interfaces/Photo';
@Injectable({
  providedIn: 'root'
})
export class PhotoService {
  private URI;
  constructor(private httpClient: HttpClient) {
    this.URI = 'http://localhost:3500/api/gallery';
  }

  savePhoto (title: string, description: string, image: any): Observable<any> {
    const newPhoto = new FormData();
    newPhoto.append('title', title);
    newPhoto.append('description', description);
    newPhoto.append('image', image);
    return this.httpClient.post(`${ this.URI }/photos`, newPhoto);
  }

  getPhotos (): Observable<any> {
    return this.httpClient.get<Photo[]>(`${ this.URI }/photos`, {});
  }

  getPhotoPreview (photoId: string): Observable<any> {
    return this.httpClient.get<Photo>(`${ this.URI }/photos/${ photoId }`, {});
  }

  deletePhoto (photoId: string): Observable<any> {
    return this.httpClient.delete(`${ this.URI }/photos/${ photoId }`, {});
  }

  updatePhotoData (photoData: any, photoId: string): Observable<any> {
    return this.httpClient.put(`${ this.URI }/photos/${ photoId }`, {
      title: photoData.title,
      description: photoData.description
    });
  }
}
