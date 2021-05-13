import dotenv from 'dotenv';
dotenv.config();
import { App } from './App';
import { Connection } from './database/Connection';

const app = new App();
const connection = new Connection();

const main = async () => {
    await app.listen();
    await connection.connectMe();
}

main();