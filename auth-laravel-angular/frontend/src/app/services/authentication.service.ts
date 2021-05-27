import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class AuthenticationService {
  private API_BACKEND = 'http://localhost:8000/api';
  constructor(private httpClient: HttpClient) { }

  public signIn (userData: any): Observable<any> {
    return this.httpClient.post( `${ this.API_BACKEND }/login` , userData);
  }


  public signUp (userData: any): Observable<any> {
    return this.httpClient.post(`${ this.API_BACKEND }/signup`, {
      name: userData.name,
      email: userData.email,
      password: userData.password
    });
  }
}
