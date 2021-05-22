import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { PokelistComponent } from './pokelist/pokelist.component';

//
import { HttpClientModule } from '@angular/common/http';
import { HeaderComponent } from './header/header.component';
import { FiltradopokemonsPipe } from './pipes/filtradopokemons.pipe';

@NgModule({
  declarations: [
    PokelistComponent,
    HeaderComponent,
    FiltradopokemonsPipe
  ],
  imports: [
    CommonModule,
    HttpClientModule //
  ],
  exports: [ //Exportando los componentes de pokemon ya que usamos un modulo para ello
    PokelistComponent,
    HeaderComponent
  ]
})
export class PokemonModule { }
