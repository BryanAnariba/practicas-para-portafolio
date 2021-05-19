import { Component, OnInit, Output, EventEmitter } from '@angular/core';

import { NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { ToastrService } from 'ngx-toastr';
import { UsersService } from 'src/app/services/users.service';
import { IUsers } from 'src/app/interfaces/iusers';
import { IPlaylists } from 'src/app/interfaces/iplaylists';

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
  }

  // Decorador para comunicarme con el componente sidebar para mostrar las playlists segun el usuario seleccionado
  @Output() onViewPlaylists = new EventEmitter();
  public getUserPlaylists = () => {
    if (this.user.length === 0) {
      this.toastr.error('Please Select an user', 'Error');
    } else {
      this.onViewPlaylists.emit({ userId: this.user });
      this.modalService.dismissAll();
    }
  }


  public savePlaylist = () => {
    if (this.user === '') {
      this.toastr.error('Please select an user', 'Error');
    } else {
      this.usersService.savePlaylist(this.newPlaylistForm.value, this.user)
      .subscribe(
        (result: any) => {
          this.modalService.dismissAll();
          this.newPlaylistForm.reset();
          this.getUserPlaylists();
          this.toastr.success('Playlist added successfully');
        },
        (error: any) => {
          this.toastr.error(error, 'Error');
        }
      );
    }
  }
}
