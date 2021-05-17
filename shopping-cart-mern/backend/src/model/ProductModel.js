const { Schema, model } = require('mongoose');

const productSchema = new Schema({
    name: {
        type: String,
        required: true
    }, 
    description: {
        type: String, 
        required: true
    },
    price: {
        type: Number,
        required: true
    }, 
    countInStock: {
        type: Number,
        required: true
    },
    imageUrl: {
        type: String,
        required: true
    }
});

module.exports = model('products', productSchema);