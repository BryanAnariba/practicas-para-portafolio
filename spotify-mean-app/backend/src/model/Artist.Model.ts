import { Schema, model } from 'mongoose';

const artists = new Schema({
    nombreArtista: {
        type: String,
        required: true
    }, 
    albumes: {
        type: Array
    }
});

export default model('artistas', artists);