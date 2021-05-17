import { Component, OnInit } from '@angular/core';
import { ToastrService } from 'ngx-toastr';
import { PhotoService } from 'src/app/services/photo.service';
import { Router } from '@angular/router';

@Component({
  selector: 'app-gallery',
  templateUrl: './gallery.component.html',
  styleUrls: ['./gallery.component.css']
})
export class GalleryComponent implements OnInit {
  public photos: any = [];
  constructor(
    private photoService: PhotoService,
    private toastr: ToastrService,
    private router: Router
    ) { }

  ngOnInit(): void {
    this.getPhotos();
  }

  selectedCard(photoId: string) {
    // redireccionamod el id
    this.router.navigate(['gallery/photos/preview', photoId]);
  }

  getPhotos () {
    this.photoService.getPhotos()
    .subscribe(
      (response: any) => {
        const { data } = response;
        console.log(data);
        this.photos = data;
      },
      (error: any) => {
        const { data } = error;
        this.toastr.success('Error', data);
      }
    );
  }
}

