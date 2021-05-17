import express, { Application } from 'express';
import { json, urlencoded } from 'body-parser';
import { resolve } from 'path';
import cors from 'cors';

import galleryRoutes from './routes/Gallery.Routes';

class Server {
    private app: Application;
    private port: string | number;
    private apiPaths = {
        gallery: '/api/gallery'
    };

    constructor () {
        this.app = express();
        this.port = process.env.BACKEND_PORT || 3600;
        
        this.middlewares();
        this.routes();
        this.staticFiles();
    }
    
    routes (): void {
        this.app.use( this.apiPaths.gallery , galleryRoutes );
    }

    middlewares (): void {
        // Configurar corst y lectura del body, carpeta publica
        this.app.use(cors({ origin: 'http://localhost:4200' }));
        this.app.use(json());
        this.app.use(urlencoded({ extended: true }));
    }

    staticFiles (): void {
        this.app.use('/uploads', express.static(resolve('uploads')));
    }


    async listen (): Promise<any> {
        await this.app.listen(this.port);
        console.log(`Server started on port ${ this.port }`);
    }
}

export default Server;