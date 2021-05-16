"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
var express_1 = require("express");
var router = express_1.Router();
var User_Controller_1 = require("../controller/User.Controller");
// Web Services for users
//     1 - Get only users
//     2 - Get Playlists With Songs
//     3 - Get Playlists
//     4 - Save song in playlist
//     5 - Save new Playlist
router.get('/', User_Controller_1.getUsers);
router.get('/:userId/playlist/:playlistId', User_Controller_1.getPlaylistsWithSongs);
router.get('/:userId/playlists', User_Controller_1.getPlaylists);
router.post('/:userId/playlist/:playlistId/song', User_Controller_1.saveSongInPlaylist);
router.post('/:userId/playlists', User_Controller_1.savePlaylist);
exports.default = router;
