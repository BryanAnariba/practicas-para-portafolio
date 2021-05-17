"use strict";
var __awaiter = (this && this.__awaiter) || function (thisArg, _arguments, P, generator) {
    function adopt(value) { return value instanceof P ? value : new P(function (resolve) { resolve(value); }); }
    return new (P || (P = Promise))(function (resolve, reject) {
        function fulfilled(value) { try { step(generator.next(value)); } catch (e) { reject(e); } }
        function rejected(value) { try { step(generator["throw"](value)); } catch (e) { reject(e); } }
        function step(result) { result.done ? resolve(result.value) : adopt(result.value).then(fulfilled, rejected); }
        step((generator = generator.apply(thisArg, _arguments || [])).next());
    });
};
var __importDefault = (this && this.__importDefault) || function (mod) {
    return (mod && mod.__esModule) ? mod : { "default": mod };
};
Object.defineProperty(exports, "__esModule", { value: true });
exports.updatePhoto = exports.deletePhoto = exports.savePhoto = exports.getPhoto = exports.getPhotos = void 0;
const PhotoModel_1 = __importDefault(require("../models/PhotoModel"));
const path_1 = require("path");
const fs_extra_1 = __importDefault(require("fs-extra"));
const getPhotos = (req, res) => __awaiter(void 0, void 0, void 0, function* () {
    try {
        const photos = yield PhotoModel_1.default.find();
        return res.status(200).send({
            status: 200,
            data: photos
        });
    }
    catch (error) {
        return res.status(400).send({
            status: 400,
            data: error
        });
    }
});
exports.getPhotos = getPhotos;
const getPhoto = (req, res) => __awaiter(void 0, void 0, void 0, function* () {
    const { photoId } = req.params;
    try {
        const photo = yield PhotoModel_1.default.find({ _id: photoId });
        return res.status(200).send({
            status: 200,
            data: photo[0]
        });
    }
    catch (error) {
        return res.status(400).send({
            status: 400,
            data: error
        });
    }
});
exports.getPhoto = getPhoto;
const savePhoto = (req, res) => __awaiter(void 0, void 0, void 0, function* () {
    const { title, description } = req.body;
    //console.log(req.file);
    const newPhoto = new PhotoModel_1.default({
        title: title,
        description: description,
        imagePath: req.file.path
    });
    try {
        yield newPhoto.save();
        return res.status(200).send({
            status: 200,
            data: 'The photo was save successfully'
        });
    }
    catch (error) {
        return res.status(400).send({
            status: 400,
            data: error
        });
    }
});
exports.savePhoto = savePhoto;
const deletePhoto = (req, res) => __awaiter(void 0, void 0, void 0, function* () {
    const { photoId } = req.params;
    try {
        const photo = yield PhotoModel_1.default.findByIdAndRemove(photoId);
        if (photo) {
            yield fs_extra_1.default.unlink(path_1.resolve(photo.imagePath));
        }
        return res.status(200).send({
            status: 200,
            data: 'The photo was deleted successfully',
            photo: photo
        });
    }
    catch (error) {
        return res.status(400).send({
            status: 400,
            data: error
        });
    }
});
exports.deletePhoto = deletePhoto;
const updatePhoto = (req, res) => __awaiter(void 0, void 0, void 0, function* () {
    const { photoId } = req.params;
    const { title, description } = req.body;
    try {
        const updated = yield PhotoModel_1.default.updateOne({ _id: photoId }, {
            title: title,
            description: description
        });
        return res.status(200).send({
            status: 200,
            data: 'The photo was updated successfully',
            photo: updated
        });
    }
    catch (error) {
        return res.status(400).send({
            status: 400,
            data: error
        });
    }
});
exports.updatePhoto = updatePhoto;
//# sourceMappingURL=Gallery.Controller.js.map