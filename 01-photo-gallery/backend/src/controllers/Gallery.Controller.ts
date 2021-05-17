import { Request, Response } from "express";
import photoModel from '../models/PhotoModel';
import { resolve } from 'path';
import fs from 'fs-extra';


export const getPhotos = async (req: Request, res: Response): Promise<Response> => {    
    try {
        const photos = await photoModel.find();
        return res.status(200).send({
            status: 200,
            data: photos
        });
    } catch (error) {
        return res.status(400).send({
            status: 400,
            data: error
        });
    }
}

export const getPhoto = async (req: Request, res: Response): Promise<Response> => {
    const { photoId } = req.params;
    try {
        const photo = await photoModel.find({ _id: photoId });
        return res.status(200).send({
            status: 200,
            data: photo[0]
        });
    } catch (error) {
        return res.status(400).send({
            status: 400,
            data: error
        });
    }
}

export const savePhoto = async (req: Request, res: Response): Promise<Response> => {
    const { title, description } = req.body;
    //console.log(req.file);
    const newPhoto = new photoModel({
        title: title,
        description: description,
        imagePath: req.file.path
    });

    try {
        await newPhoto.save();
        return res.status(200).send({
            status: 200,
            data: 'The photo was save successfully'
        });
    } catch (error) {
        return res.status(400).send({
            status: 400,
            data: error
        });
    }
    
}

export const deletePhoto = async (req: Request, res: Response) => {
    const { photoId } = req.params;
    try {
        const photo = await photoModel.findByIdAndRemove(photoId);
        if (photo) {
            await fs.unlink(resolve(photo.imagePath));
        }
        return res.status(200).send({
            status: 200,
            data: 'The photo was deleted successfully',
            photo: photo
        });
    } catch (error) {
        return res.status(400).send({
            status: 400,
            data: error
        });
    }
}

export const updatePhoto = async (req: Request, res: Response): Promise<Response> => {
    const { photoId } = req.params;
    const { title, description } = req.body;
    try {
        const updated = await photoModel.updateOne({ _id: photoId }, {
            title: title,
            description: description
        });
        return res.status(200).send({
            status: 200,
            data: 'The photo was updated successfully',
            photo: updated
        });
    } catch (error) {
        return res.status(400).send({
            status: 400,
            data: error
        });
    }
}