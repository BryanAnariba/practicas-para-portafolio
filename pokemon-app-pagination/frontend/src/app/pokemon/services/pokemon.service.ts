import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { FetchAllPokemonResponse, Pokemon } from '../interfaces/pokemon.interface';


import { map } from 'rxjs/operators';
import { Observable } from 'rxjs';
@Injectable({
  providedIn: 'root'
})
export class PokemonService {
  private URL:string = 'https://pokeapi.co/api/v2';
  constructor(
    private httpClient: HttpClient
  ) { }

  getPokemons = (): Observable<Pokemon[]> => {
    return this.httpClient.get<FetchAllPokemonResponse>(`${ this.URL }/pokemon?limit=1200`,{})
    .pipe( map( this.transformSmallPokemonInPokemon ) );
  }

  private transformSmallPokemonInPokemon = (res: FetchAllPokemonResponse): Pokemon[] => {
    const pokemonList: Pokemon[] = res.results.map( pokemon => {
      const urlAarr = pokemon.url.split('/');
      const id = urlAarr[6];
      const pic = `https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/${ id }.png`;

      return {
        id: id,
        name: pokemon.name,
        pic: pic
      }
    })

    return pokemonList;
  }
}
