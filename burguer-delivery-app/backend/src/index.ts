import dotenv from 'dotenv';
dotenv.config();

import { Connection } from './database/Connection';
import App from './App';

const main = async (): Promise<void> => {
    const database = new Connection();
    const server = new App();
    await server.startServer();
    await database.connection();
}

main();