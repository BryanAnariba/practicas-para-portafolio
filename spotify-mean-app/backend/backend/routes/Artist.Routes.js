"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
var express_1 = require("express");
var Artist_Controller_1 = require("../controller/Artist.Controller");
var router = express_1.Router();
// Web Services for playlists 
//     1 - Get artists
//     2 - Get albumes
router.get('/', Artist_Controller_1.getArtists);
router.get('/:artistId/albumes', Artist_Controller_1.getAlbumesWithSongs);
exports.default = router;
