import { Component, EventEmitter, OnInit, Output } from '@angular/core';

import { faMusic, faPlay, faPlus } from '@fortawesome/free-solid-svg-icons';
import { NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { ToastrService } from 'ngx-toastr';
import { IAlbumes } from 'src/app/interfaces/ialbumes';
import { ArtistsService } from 'src/app/services/artists.service';
import { DomSanitizer } from '@angular/platform-browser';
import { UsersService } from 'src/app/services/users.service';
import { IPlaylists } from 'src/app/interfaces/iplaylists';

@Component({
  selector: 'app-albumes',
  templateUrl: './albumes.component.html',
  styleUrls: ['./albumes.component.scss']
})
export class AlbumesComponent implements OnInit {
  @Output() refreshPlaylists = new EventEmitter;

  public artistSelected: any = {};
  public albumes:IAlbumes[] =  [];
  public playlists: IPlaylists[] = [];
  public userSelected: string = '';
  public playlistSelected: string = '';
  public song: any = {};
  public album: string = '';

  faMusic = faMusic;
  faPlay = faPlay;
  faPlus = faPlus;
  constructor(
    private modalService: NgbModal,
    private artistsService: ArtistsService,
    private toastr: ToastrService,
    private domSanitizer: DomSanitizer,
    private userService: UsersService
  ) { }

  ngOnInit(): void {
  }

  public getAlbumes = (artistData: any) => {
    this.artistSelected = artistData;
    this.artistsService.getAlbumes(artistData)
    .subscribe(
      (albumes) => {
        this.albumes = albumes.data.albumes;
        console.log(this.albumes);
      },
      (error: any) => {
        this.toastr.error(error, 'Error');
      }
    );
  }

  public sanitizeImg = (image: string) => {
    return this.domSanitizer.bypassSecurityTrustStyle(`url(assets/${ image })`);
  }

  modalSaveSongInPlaylist = (playlistModal: any, song: any, album: string) => {
    this.album = album;
    this.song = song;
    this.userService.getPlaylistByUser(this.userSelected)
    .subscribe(
      (playlists) => {
        this.playlists = playlists.data.playlists;
        console.log(this.playlists);
        this.modalService.open(playlistModal,{ size: 'md', centered: true });
      },
      (error) => {
        this.toastr.error(error, 'Error');
      }
    );
  }

  public saveSongInPlaylist = () => {
    if (this.playlistSelected === "") {
      this.toastr.error('Select a playlist', 'Error');
    } else {
      this.userService.saveSongInPlaylist(this.song, this.album, this.userSelected, this.playlistSelected)
      .subscribe(
        (result: any) => {
          this.toastr.success('Song Added Successfully');
          this.refreshPlaylists.emit();
          this.modalService.dismissAll();
        },
        (error: any) => {
          this.toastr.error(error, 'Error');
        }
      );
    }
  }

}
