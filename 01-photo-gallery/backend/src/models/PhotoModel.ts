import { Schema, model, Document } from 'mongoose';

interface IPhoto extends Document {
    title: string;
    description: string;
    imagePath: string;
}

const photoSchema = new Schema({
    title: {
        type: String,
        required: true
    },
    description: {
        type: String,
        required: true
    },
    imagePath: {
        type: String,
        required: true
    },
});

export default model<IPhoto>('Gallery', photoSchema);