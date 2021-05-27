import { Component, OnInit } from '@angular/core';

import { faUser, faLock } from '@fortawesome/free-solid-svg-icons';
import { Validators, FormGroup, FormControl } from '@angular/forms';
import { AuthenticationService } from 'src/app/services/authentication.service';
import { ToastrService } from 'ngx-toastr';
import { AccessTokenService } from '../../services/access-token.service';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css']
})
export class LoginComponent implements OnInit {
  public faUser = faUser;
  public faLock = faLock;
  public userCredentials = new FormGroup({
    email: new FormControl('', [ Validators.required, Validators.pattern(/^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i) ]),
    password: new FormControl('', [ Validators.required ])
  });

  get email () {
    return this.userCredentials.get('email');
  }

  get password () {
    return this.userCredentials.get('password');
  }

  public signIn = () => {
    this.authenticationService.signIn( this.userCredentials.value )
    .subscribe(
      (response: any) => {
        console.log(response);
        this.accessToken.getToken( response.access_token );
      },
      ({ error }) => {
        this.toastr.error(error.error,'Error');
      }
    );
  }

  constructor(
    private authenticationService: AuthenticationService,
    private toastr: ToastrService,
    private accessToken: AccessTokenService
    ) { }

  ngOnInit(): void {
  }
}
