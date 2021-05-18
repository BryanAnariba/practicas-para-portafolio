import { Component, OnInit } from '@angular/core';

import { NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { ToastrService } from 'ngx-toastr';
import { UsersService } from 'src/app/services/users.service';
import { IUsers } from 'src/app/interfaces/iusers';
import { IPlaylists } from 'src/app/interfaces/iplaylists';
import { THIS_EXPR } from '@angular/compiler/src/output/output_ast';

@Component({
  selector: 'app-header',
  templateUrl: './header.component.html',
  styleUrls: ['./header.component.scss']
})
export class HeaderComponent implements OnInit {
  constructor(
    private modalService: NgbModal,
    private toastr: ToastrService,
    private usersService: UsersService
  ) { }

  public users: IUsers[] = [];
  public user: string = '';
  public playlists: IPlaylists[] = [];

  public newPlaylistForm = new FormGroup({
    tituloPlayList: new FormControl('', [Validators.required, Validators.minLength(4), Validators.maxLength(80)])
  });

  get tituloPlayList () {
    return this.newPlaylistForm.get('tituloPlayList');
  }

  ngOnInit(): void {
  }

  createNewPlaylist = (playlistModal: any) => {
    this.modalService.open(playlistModal,{ size: 'md', centered: true });
  }

  createNewUser = (userModal: any) => {
    this.getUsers();
    this.modalService.open(userModal,{ size: 'md', centered: true });
  }

  getUsers = () => {
    this.usersService.getUsers()
    .subscribe(
      (users: any) => {
        this.users = users.data;
        console.log(this.users);
      },
      (error: any) => {
        console.log(error);
      }
    );
  }

  public userSelected = (userSelected: any) => {
    this.user = userSelected.target.value;
    console.log(this.user);
  }

  public getUserPlaylists = () => {
    if (this.user.length === 0) {
      this.toastr.error('Please Select an user', 'Error');
    } else {
      this.usersService.getPlaylistByUser( this.user )
      .subscribe(
        (playlists: any) => {
          this.playlists = playlists.data;
          console.log(this.playlists);
        },
        (error: any) => {
          this.toastr.error(error, 'Error');
        }
      );
    }
  }

  public savePlaylist = () => {

  }
}
