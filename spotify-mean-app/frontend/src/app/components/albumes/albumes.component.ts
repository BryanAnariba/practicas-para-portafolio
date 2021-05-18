import { Component, OnInit } from '@angular/core';

import { faMusic, faPlay, faPlus } from '@fortawesome/free-solid-svg-icons';
import { NgbModal } from '@ng-bootstrap/ng-bootstrap';

@Component({
  selector: 'app-albumes',
  templateUrl: './albumes.component.html',
  styleUrls: ['./albumes.component.scss']
})
export class AlbumesComponent implements OnInit {

  faMusic = faMusic;
  faPlay = faPlay;
  faPlus = faPlus;
  constructor(
    private modalService: NgbModal
  ) { }

  ngOnInit(): void {
  }

  modalSaveSongInPlaylist = (playlistModal: any) => {
    this.modalService.open(playlistModal,{ size: 'md', centered: true });
  }

  saveSongInPlaylist = () => {
  }

}
