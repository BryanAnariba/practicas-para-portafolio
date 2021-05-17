"use strict";
var __importDefault = (this && this.__importDefault) || function (mod) {
    return (mod && mod.__esModule) ? mod : { "default": mod };
};
Object.defineProperty(exports, "__esModule", { value: true });
const express_1 = require("express");
const Gallery_Controller_1 = require("../controllers/Gallery.Controller");
const Multer_1 = __importDefault(require("../helpers/Multer"));
const router = express_1.Router();
router.get('/photos', Gallery_Controller_1.getPhotos);
router.get('/photos/:photoId', Gallery_Controller_1.getPhoto);
router.post('/photos', Multer_1.default.single('image'), Gallery_Controller_1.savePhoto);
router.delete('/photos/:photoId', Gallery_Controller_1.deletePhoto);
router.put('/photos/:photoId', Gallery_Controller_1.updatePhoto);
exports.default = router;
//# sourceMappingURL=Gallery.Routes.js.map