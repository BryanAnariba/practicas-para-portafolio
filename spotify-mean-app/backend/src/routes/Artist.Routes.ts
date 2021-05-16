import { Router } from 'express';
import { 
    getArtists, 
    getAlbumesWithSongs 
} from '../controller/Artist.Controller';
const router = Router();

// Web Services for playlists 
//     1 - Get artists
//     2 - Get albumes

router.get('/', getArtists);
router.get('/:artistId/albumes', getAlbumesWithSongs);
export default router;