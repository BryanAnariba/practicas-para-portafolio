import express, { Application, json, urlencoded } from 'express';
import cors from 'cors';

import UserRoutes from './routes/User.Routes';
import AuthRoutes from './routes/Auth.Routes';
import OrderRoutes from './routes/Order.Routes';
import ProductsRoutes from './routes/Products.Routes';

class App {
    private app: Application;
    protected port: string | number;

    private endPoints = {
        auth: '/api',
        users: '/api/users',
        products: '/api/products',
        orders: '/api/orders'
    };

    public constructor () {
        this.port = process.env.PORT || 3600;
        this.app = express();
        this.settings();
        this.middlewares();
        this.routes();
        this.staticFiles();
    }

    public settings = (): void => {
        this.app.set('port', this.port);
    }

    public middlewares = (): void => {
        this.app.use(cors());
        this.app.use(json());
        this.app.use(urlencoded({ extended: true }));
    }

    public routes = (): void => {
        this.app.use( this.endPoints.auth, AuthRoutes );
        this.app.use( this.endPoints.users, UserRoutes );
        this.app.use( this.endPoints.products, ProductsRoutes );
        this.app.use( this.endPoints.orders, OrderRoutes );
    }

    public staticFiles = (): void => {
    }

    public startServer = async (): Promise<void> => {
        await this.app.listen( this.app.get('port') );
        console.log(`Server Started on port ${ this.port }`);
    }
}

export default App;