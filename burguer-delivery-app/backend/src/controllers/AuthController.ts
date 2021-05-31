import { Request, Response } from 'express';
import { IUser } from '../interfaces/IUser';
import User from '../models/User';

export const signUp = async (req: Request, res: Response): Promise<Response> => {
    const { firstName, lastName, emailUser, password, avatar }: IUser = req.body;

    const newUser = new User({
        firstName: firstName,
        lastName: lastName,
        emailUser: emailUser,
        password: password,
        avatar: avatar
    });

    return res.status(200).send(newUser);
}