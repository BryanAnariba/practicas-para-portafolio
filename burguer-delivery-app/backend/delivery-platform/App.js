"use strict";
var __createBinding = (this && this.__createBinding) || (Object.create ? (function(o, m, k, k2) {
    if (k2 === undefined) k2 = k;
    Object.defineProperty(o, k2, { enumerable: true, get: function() { return m[k]; } });
}) : (function(o, m, k, k2) {
    if (k2 === undefined) k2 = k;
    o[k2] = m[k];
}));
var __setModuleDefault = (this && this.__setModuleDefault) || (Object.create ? (function(o, v) {
    Object.defineProperty(o, "default", { enumerable: true, value: v });
}) : function(o, v) {
    o["default"] = v;
});
var __importStar = (this && this.__importStar) || function (mod) {
    if (mod && mod.__esModule) return mod;
    var result = {};
    if (mod != null) for (var k in mod) if (k !== "default" && Object.prototype.hasOwnProperty.call(mod, k)) __createBinding(result, mod, k);
    __setModuleDefault(result, mod);
    return result;
};
var __awaiter = (this && this.__awaiter) || function (thisArg, _arguments, P, generator) {
    function adopt(value) { return value instanceof P ? value : new P(function (resolve) { resolve(value); }); }
    return new (P || (P = Promise))(function (resolve, reject) {
        function fulfilled(value) { try { step(generator.next(value)); } catch (e) { reject(e); } }
        function rejected(value) { try { step(generator["throw"](value)); } catch (e) { reject(e); } }
        function step(result) { result.done ? resolve(result.value) : adopt(result.value).then(fulfilled, rejected); }
        step((generator = generator.apply(thisArg, _arguments || [])).next());
    });
};
var __importDefault = (this && this.__importDefault) || function (mod) {
    return (mod && mod.__esModule) ? mod : { "default": mod };
};
Object.defineProperty(exports, "__esModule", { value: true });
const express_1 = __importStar(require("express"));
const cors_1 = __importDefault(require("cors"));
const User_Routes_1 = __importDefault(require("./routes/User.Routes"));
const Auth_Routes_1 = __importDefault(require("./routes/Auth.Routes"));
const Order_Routes_1 = __importDefault(require("./routes/Order.Routes"));
const Products_Routes_1 = __importDefault(require("./routes/Products.Routes"));
class App {
    constructor() {
        this.endPoints = {
            auth: '/api',
            users: '/api/users',
            products: '/api/products',
            orders: '/api/orders'
        };
        this.settings = () => {
            this.app.set('port', this.port);
        };
        this.middlewares = () => {
            this.app.use(cors_1.default());
            this.app.use(express_1.json());
            this.app.use(express_1.urlencoded({ extended: true }));
        };
        this.routes = () => {
            this.app.use(this.endPoints.auth, Auth_Routes_1.default);
            this.app.use(this.endPoints.users, User_Routes_1.default);
            this.app.use(this.endPoints.products, Products_Routes_1.default);
            this.app.use(this.endPoints.orders, Order_Routes_1.default);
        };
        this.staticFiles = () => {
        };
        this.startServer = () => __awaiter(this, void 0, void 0, function* () {
            yield this.app.listen(this.app.get('port'));
            console.log(`Server Started on port ${this.port}`);
        });
        this.port = process.env.PORT || 3600;
        this.app = express_1.default();
        this.settings();
        this.middlewares();
        this.routes();
        this.staticFiles();
    }
}
exports.default = App;
