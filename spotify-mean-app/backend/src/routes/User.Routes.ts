import { Router } from 'express';
const router = Router();

import { 
    getUsers, 
    getPlaylistsWithSongs, 
    getPlaylists, 
    savePlaylist, 
    saveSongInPlaylist 
} from '../controller/User.Controller';

// Web Services for users
//     1 - Get only users
//     2 - Get Playlists With Songs
//     3 - Get Playlists
//     4 - Save song in playlist
//     5 - Save new Playlist

router.get('/', getUsers);
router.get('/:userId/playlist/:playlistId', getPlaylistsWithSongs);
router.get('/:userId/playlists', getPlaylists);
router.post('/:userId/playlist/:playlistId/song', saveSongInPlaylist);
router.post('/:userId/playlists', savePlaylist);

export default router;