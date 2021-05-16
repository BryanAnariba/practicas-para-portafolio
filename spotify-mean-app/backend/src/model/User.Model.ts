import { Schema, model } from 'mongoose';

const users = new Schema({
    nombreUsuario: {
        type: String,
        required: true
    },
    playlists: {
        type: Array
    }
});

export default model('usuarios', users);