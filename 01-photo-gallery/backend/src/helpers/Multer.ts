import multer from 'multer';
import { v4 as uuidv4 } from 'uuid';
import { extname } from 'path';

const storage = multer.diskStorage({
    destination: 'uploads',
    filename(req, file, cb) {
        cb(null, uuidv4() + extname(file.originalname));
    }
});


export default multer({ storage });