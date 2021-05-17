import { Router } from 'express';
import { deletePhoto, getPhotos, savePhoto, updatePhoto, getPhoto } from '../controllers/Gallery.Controller';
import multer from '../helpers/Multer';

const router = Router();

router.get('/photos', getPhotos);
router.get('/photos/:photoId', getPhoto);
router.post('/photos', multer.single('image') ,savePhoto);
router.delete('/photos/:photoId', deletePhoto);
router.put('/photos/:photoId', updatePhoto);

export default router;