"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
var mongoose_1 = require("mongoose");
var users = new mongoose_1.Schema({
    nombreUsuario: {
        type: String,
        required: true
    },
    playlists: {
        type: Array
    }
});
exports.default = mongoose_1.model('usuarios', users);
