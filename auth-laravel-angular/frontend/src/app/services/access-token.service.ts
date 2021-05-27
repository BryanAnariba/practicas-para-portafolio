import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root'
})
export class AccessTokenService {
  private iss: any = {
    login: 'http://localhost:8000/api/login',
    signup: 'http://localhost:8000/api/signup'
  };
  constructor() { }

  public setTokenInStorage = (token: string) => {
    localStorage.setItem('token', token);
  }

  public getTokenInStorage = () => {
    return localStorage.getItem('token');
  }

  public removeTokenInStorage = () => {
    localStorage.removeItem('token');
  }

  public isValidTokenInStorage = () => {
    const token = this.getTokenInStorage();
    if (token) {
      const payload = this.payload(token);
      if (payload) {
        return Object.values(this.iss).indexOf(payload.iss) >-1 ? true: false;
      }
    }
    return false;
  }

  public payload = (token: string) => {
    const payload = token.split('.')[1];
    return this.decodedToken(payload);
  }

  public decodedToken = (payload: any) => {
    return JSON.parse(atob(payload));
  }

  public getToken = (token: string) => {
    this.setTokenInStorage(token);
    console.log(this.isValidTokenInStorage());
  }

  public isLogged = () => {
    return this.isValidTokenInStorage();
  }
}
