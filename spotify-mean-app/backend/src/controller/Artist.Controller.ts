import { Request, Response } from 'express';
import ArtistModel from '../model/Artist.Model';

export const getArtists = async (req: Request, res: Response): Promise<Response> => {
    try {
        const artists = await ArtistModel.find({  }, { nombreArtista: true });
        return res.status(200).send({ status: 200, data: artists });
    } catch(error) {
        return res.status(404).send({ status: 404, data: error });
    }

}

export const getAlbumesWithSongs = async (req: Request, res: Response): Promise<Response> => {
    const { artistId } = req.params;
    try {
        const artistWithTheirAlbumes = await ArtistModel.find({ _id: artistId }, { albumes: true });
        return res.status(200).send({ status: 200, data: artistWithTheirAlbumes[0] });
    } catch(error) {
        return res.status(404).send({ status: 404, data: error });
    }
}

