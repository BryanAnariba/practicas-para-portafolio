import { Component, OnInit } from '@angular/core';

import { faUser, faLock } from '@fortawesome/free-solid-svg-icons';
import { Validators, FormGroup, FormControl } from '@angular/forms';
import { AuthenticationService } from 'src/app/services/authentication.service';
import { ToastrService } from 'ngx-toastr';

@Component({
  selector: 'app-signup',
  templateUrl: './signup.component.html',
  styleUrls: ['./signup.component.css']
})
export class SignupComponent implements OnInit {
  public faUser = faUser;
  public faLock = faLock;
  public userCredentials = new FormGroup({
    name: new FormControl('', [ Validators.required, Validators.minLength(2), Validators.maxLength(80), Validators.pattern(/^[ÁÉÍÓÚA-Z][a-záéíóú]+(\s+[ÁÉÍÓÚA-Z]?[a-záéíóú]+)*$/
    ) ]),
    email: new FormControl('', [ Validators.required, Validators.pattern(/^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i) ]),
    password: new FormControl('', [ Validators.required ]),
    confirmPassword: new FormControl('', [ Validators.required ])
  });

  get email () {
    return this.userCredentials.get('email');
  }

  get password () {
    return this.userCredentials.get('password');
  }

  get name () {
    return this.userCredentials.get('name');
  }

  get confirmPassword() {
    return this.userCredentials.get('confirmPassword');
  }

  public signUp = () => {
    if ( this.userCredentials.get('password')?.value === this.userCredentials.get('confirmPassword')?.value ) {
      this.authenticationService.signUp(this.userCredentials.value)
      .subscribe(
        (response: any) => {
          console.log(response);
        },
        (error: any) => {
          console.error(error);
        }
      );
    } else {
      this.toastr.error('Passwords doest not match', 'Error');
    }
  }

  constructor(
    private authenticationService: AuthenticationService,
    private toastr: ToastrService
    ) { }

  ngOnInit(): void {
  }

}
