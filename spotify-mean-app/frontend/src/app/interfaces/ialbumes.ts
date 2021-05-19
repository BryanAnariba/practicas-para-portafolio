export interface IAlbumes extends ICanciones{
  caratulaAlbum: string;
  nombreAlbum: string;
  canciones: ICanciones[]
}

interface ICanciones {
  nombreCancion: string;
  duracion: string;
}
