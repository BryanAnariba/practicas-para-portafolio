"use strict";
var __awaiter = (this && this.__awaiter) || function (thisArg, _arguments, P, generator) {
    function adopt(value) { return value instanceof P ? value : new P(function (resolve) { resolve(value); }); }
    return new (P || (P = Promise))(function (resolve, reject) {
        function fulfilled(value) { try { step(generator.next(value)); } catch (e) { reject(e); } }
        function rejected(value) { try { step(generator["throw"](value)); } catch (e) { reject(e); } }
        function step(result) { result.done ? resolve(result.value) : adopt(result.value).then(fulfilled, rejected); }
        step((generator = generator.apply(thisArg, _arguments || [])).next());
    });
};
Object.defineProperty(exports, "__esModule", { value: true });
exports.Connection = void 0;
const mongoose_1 = require("mongoose");
class Connection {
    constructor() {
        this.dataBase = 'PhotoGallery';
        this.port = 27017;
        this.host = 'localhost';
    }
    connectMe() {
        return __awaiter(this, void 0, void 0, function* () {
            try {
                yield mongoose_1.connect(`mongodb://${this.host}:${this.port}/${this.dataBase}`, {
                    useNewUrlParser: true,
                    useUnifiedTopology: true,
                    useCreateIndex: true
                });
                console.log('MongoDB is Connected!');
            }
            catch (error) {
                console.log(error);
            }
        });
    }
}
exports.Connection = Connection;
//# sourceMappingURL=Connection.js.map