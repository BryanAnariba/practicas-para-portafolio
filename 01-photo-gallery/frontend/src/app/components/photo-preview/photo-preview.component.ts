import { Component, OnInit } from '@angular/core';
import { ToastrService } from 'ngx-toastr';
import { PhotoService } from 'src/app/services/photo.service';
import { ActivatedRoute, Router } from '@angular/router';
import { Photo } from 'src/app/interfaces/Photo';

import { Validators, FormControl, FormGroup } from '@angular/forms';


@Component({
  selector: 'app-photo-preview',
  templateUrl: './photo-preview.component.html',
  styleUrls: ['./photo-preview.component.css']
})
export class PhotoPreviewComponent implements OnInit {
  photoId!: string;
  public photo?: Photo;
  constructor(
    private photoService: PhotoService,
    private toastr: ToastrService,
    private router: Router,
    private activeRoute: ActivatedRoute
  ) {

  }

  public updatePhotoData = new FormGroup({
    title: new FormControl('', [Validators.required, Validators.maxLength(60), Validators.minLength(1)]),
    description: new FormControl('', [Validators.required, Validators.maxLength(150), Validators.minLength(1)])
  });

  get title () {
    return this.updatePhotoData.get('title');
  }

  get description () {
    return this.updatePhotoData.get('description');
  }

  ngOnInit(): void {
    // Extraemos el parametro necesitado desde el ngOnInit que es el primer metodo que se carga al renderizar componente
    this.activeRoute.params.subscribe(
      params => {
        this.photoId = params['photoId'];
        this.getPhoto(params['photoId']);

      }
    );
  }

  getPhoto (photoId: string) {
    this.photoService.getPhotoPreview(photoId)
    .subscribe(
      (response: any) => {
        const { data } = response;
        this.photo = data;
        this.updatePhotoData.get('title')?.setValue(data.title);
        this.updatePhotoData.get('description')?.setValue(data.description);
      },
      (error: any) => {
        const { data } = error;
        this.toastr.success('Error', data);
      }
    );
  }

  updatePhoto () {
    this.photoService.updatePhotoData(this.updatePhotoData.value, this.photoId)
    .subscribe(
      (response: any) => {
        const { data } = response;
        this.router.navigate(['gallery/photos']);
        this.toastr.success(data, 'Success');
      },
      (error: any) => {
        const { data } = error;
        this.toastr.error('Error', data);
      }
    );
  }

  deletePhoto () {
    this.photoService.deletePhoto(this.photoId)
    .subscribe(
      (response: any) => {
        const { data } = response;
        this.router.navigate(['gallery/photos']);
        this.toastr.success(data, 'Success');
      },
      (error: any) => {
        const { data } = error;
        this.toastr.error('Error', data);
      }
    )
  }
}
