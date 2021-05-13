import express, { Application, json, urlencoded } from 'express'
import cors from 'cors';
import artistRouter from './routes/Artist.Routes';
import userRouter from './routes/User.Routes';

export class App {
    private app: Application;
    private port: string | number;
    private apiPaths = {
        users: '/api/users',
        artists: '/api/artists'
    }; 

    constructor () {
        this.app = express();
        this.port = process.env.PORT || 3600;
        this.settings();
        this.middlewares();
        this.routes();
    }

    settings (): void {
        this.app.use( cors({ origin: '*', methods: [ 'GET', 'POST', 'PUT', 'DELETE' ] }) );
    }

    routes (): void {
        this.app.use(this.apiPaths.artists, artistRouter);
        this.app.use(this.apiPaths.users, userRouter);
    }

    middlewares (): void {
        // Configurar corst y lectura del body, carpeta publica
        this.app.use(cors());
        this.app.use(json());
        this.app.use(urlencoded({ extended: true }));
    }

    async listen (): Promise<any> {
        await this.app.listen(this.port);
        console.log(`Server started on port ${ this.port }`);
    }
}