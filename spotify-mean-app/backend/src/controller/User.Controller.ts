import { Request, Response } from 'express';
import UserModel from '../model/User.Model';
import { Types } from 'mongoose';

export const getUsers = (req: Request, res: Response) => {
    UserModel.find({},{ nombreUsuario: true })
    .then((users) => {
        return res.status(200).send({
            status: 200,
            data: users
        });
    })
    .catch((error) => {
        return res.status(404).send({
            status: 404,
            data: error
        });
    });
}

export const getPlaylistsWithSongs = async (req: Request, res: Response): Promise<Response> => {
    const { userId, playlistId } = req.params;

    try {
        const playlistSelected = await UserModel.find({
            _id: userId,
            "playlists._id": Types.ObjectId(playlistId)
        },{ _id: true, nombreUsuario: true, "playlists.$": true });

        return res.status(200).send({
            status: 200,
            data: playlistSelected[0]
        });
    } catch (error) {
        return res.status(404).send({
            status: 404,
            data: error
        });
    }
} 

export const getPlaylists = (req: Request, res: Response) => {
    const { userId } = req.params;
    const playlists = UserModel.find({ _id: userId }, { playlists: true })
    .then((playlists) => {
        return res.status(200).send({
            status: 200,
            data: playlists[0]
        })
    })
    .catch((error) => {
        return res.status(404).send({
            status: 404,
            data: error
        });
    })
} 

export const saveSongInPlaylist = (req: Request, res: Response) => {
    const { nombreCancion, artista, album } = req.body;
    const { userId, playlistId } = req.params;

    UserModel.updateOne(
        {
            _id: Types.ObjectId(userId),
            "playlists._id": Types.ObjectId(playlistId)
        },
        {
            $push: {
                "playlists.$.canciones": {
                    nombreCancion: nombreCancion,
                    artista: artista,
                    album: album
                }
            }
        }
    )
    .then((songSaved) => {
        return res.status(200).send({
            status: 200,
            data: songSaved
        });
    })
    .catch((error) => {
        return res.status(404).send({
            status: 404,
            data: error
        });
    })
    
}

export const savePlaylist = (req: Request, res: Response) => {
    const { userId } = req.params;
    const { tituloPlayList } = req.body;
    UserModel.updateOne({ _id: userId }, {
        $push: {
            playlists: {
                _id: Types.ObjectId(),
                tituloPlayList: tituloPlayList,
                canciones: []
            }   
        }
    })
    .then((playlistSaved) => {
        return res.status(200).send({
            status: 200,
            data: playlistSaved
        });
    })
    .catch((error) => {
        return res.status(404).send({
            status: 404,
            data: error
        });
    });
}