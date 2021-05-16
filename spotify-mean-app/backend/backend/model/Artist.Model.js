"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
var mongoose_1 = require("mongoose");
var artists = new mongoose_1.Schema({
    nombreArtista: {
        type: String,
        required: true
    },
    albumes: {
        type: Array
    }
});
exports.default = mongoose_1.model('artistas', artists);
