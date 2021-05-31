import { Schema, model } from 'mongoose';
import { IUser } from '../interfaces/IUser';

const user = new Schema({
    firstName: {
        type: String,
        required: true
    },
    lastName: {
        type: String,
        required: true
    },
    emailUser: {
        type: String,
        unique: true,
        required: true
    },
    password: {
        type: String,
        required: true
    },
    avatar: {
        type: String
    },
    accessToken: {
        type: String
    }
});

export default model<IUser>( 'users', user );