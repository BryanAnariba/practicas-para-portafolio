import { Pipe, PipeTransform } from '@angular/core';
import { Pokemon } from '../interfaces/pokemon.interface';

@Pipe({
  name: 'filtradopokemons'
})
export class FiltradopokemonsPipe implements PipeTransform {

  transform(pokemons: Pokemon[], page: number = 0, pokemon: string = ''): Pokemon[] {
    // Si no hay texto en la casilla de busqueda que mande los pokemons tal cual
    if ( pokemon.length === 0 ) {
      return pokemons.slice(page, page + 4);
    } else { // caso contrario hacer la busqueda por x poquemon y enviar los resultados
      // Filtramos los pokemons por el pokemon
      const pokemonsFiltered = pokemons.filter( (pokemonsFounds) => pokemonsFounds.name.includes( pokemon )  );

      // Retornamos los pokemons filtrado
      return pokemonsFiltered.slice(page, page + 4);
    }
  }
}
