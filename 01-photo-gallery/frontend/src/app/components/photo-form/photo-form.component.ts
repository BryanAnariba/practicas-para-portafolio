import { Component, OnInit } from '@angular/core';
import { Validators, FormControl, FormGroup } from '@angular/forms';
import { ToastrService } from 'ngx-toastr';
import { PhotoService } from 'src/app/services/photo.service';
import { Router } from '@angular/router';

// interface HtmlInputEvent extends Event {
//   target: HTMLInputElement & EventTarget | null;
// }

@Component({
  selector: 'app-photo-form',
  templateUrl: './photo-form.component.html',
  styleUrls: ['./photo-form.component.css']
})
export class PhotoFormComponent implements OnInit {

  private file?: File;
  public photoSelected?: string | ArrayBuffer | null;
  public newPhoto = new FormGroup({
    title: new FormControl('', [Validators.required, Validators.maxLength(60), Validators.minLength(1)]),
    description: new FormControl('', [Validators.required, Validators.maxLength(150), Validators.minLength(1)]),
    image: new FormControl('', [Validators.required, Validators.pattern(/\.(jpg|png|gif)$/i)]),
  });

  get title () {
    return this.newPhoto.get('title');
  }

  get description () {
    return this.newPhoto.get('description');
  }

  get image () {
    return this.newPhoto.get('image');
  }
  constructor(
    private photoService: PhotoService,
    private toastr: ToastrService,
    private router: Router
  ) { }

  ngOnInit(): void {
  }

  previewPhoto (event: any /*HtmlInputEvent*/): void {

    //  Si al dar click hay un elemento subido
    if (event.target?.files && event.target?.files[0]) {
      this.file = <File>event.target?.files[0];
      //console.log(this.file);

      if ((this.file.name).match(/\.(jpg|png|gif)$/i)) {
          // Generando un previo de la imagen
        const reader = new FileReader();
        reader.onload = e => this.photoSelected = reader.result;
        reader.readAsDataURL(this.file);
        //console.log(this.newPhoto.value)
        // Establecemos el valor ala propoedad del newPhoto con el arhivo image
        this.newPhoto.get('image')?.setValue(this.file.name, {emitModelToViewChange: false});
      } else {
        this.newPhoto?.get('image')?.setValue(null);
        this.photoSelected = null;
      }
    }
  }

  savePhoto () {
    this.photoService.savePhoto(
      this.newPhoto.get('title')?.value,
      this.newPhoto.get('description')?.value,
      this.file
      )
    .subscribe(
      (response: any) => {
        console.log(response);
        const { data } = response;
        this.toastr.success('Success', data);
        this.router.navigate(['gallery/photos']);
        this.newPhoto.reset();
        this.photoSelected = null;
      },
      (error:any) => {
        console.log(error);

        const { data } = error;
        this.toastr.success('Error', data);
      }
    )
  }
}
