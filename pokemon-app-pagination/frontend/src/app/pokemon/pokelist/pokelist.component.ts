import { Component, OnInit } from '@angular/core';
import { Pokemon } from '../interfaces/pokemon.interface';
import { PokemonService } from '../services/pokemon.service';


@Component({
  selector: 'app-pokelist',
  templateUrl: './pokelist.component.html',
  styleUrls: ['./pokelist.component.scss']
})
export class PokelistComponent implements OnInit {
  pokemon: string = '';
  constructor(private pokemonService: PokemonService) { }
  public pokemons: Pokemon[] = [];
  public page: number = 0;
  ngOnInit(): void {
    this.pokemonService.getPokemons()
    .subscribe(
      (data) => {
        console.log(data);
        this.pokemons = data;
      },
      (error) => {
        console.log(error);
      });
  }

  nextPage = () => {
    this.page += 4;
  }

  prevPage = () => {
    if (this.page > 0) {
      this.page -= 4;
    }
  }

  searchPokemon(pokemon: string) {
    this.page = 0;// para volver a los primeros pokemones en la api siempre que no haya nada en la casilla de texto
    this.pokemon = pokemon;
  }
}
