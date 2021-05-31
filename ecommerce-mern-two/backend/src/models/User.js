const mongoose = require('mongoose');

const userSchema = mongoose.Schema({
    firstName: {
        type:String,
        maxlength:50,
        required: true
    },
    email: {
        type:String,
        trim:true,
        unique: true,
        required: true
    },
    password: {
        type: String,
        minglength: 8,
        required: true
    },
    lastName: {
        type:String,
        maxlength: 50,
        required: true
    },
    role : {
        type:Number,
        default: 0 
    },
    image: String,
    token : {
        type: String,
    },
    tokenExp :{
        type: Number
    }
});

const User = mongoose.model('User', userSchema);

module.exports = { User }