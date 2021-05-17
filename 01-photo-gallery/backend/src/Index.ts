import dotenv from 'dotenv';
dotenv.config();
import Server from './Server';
import { Connection } from './database/Connection';

const server = new Server();
const connection =  new Connection();

const main = async () => {
    await server.listen();
    await connection.connectMe();
}

main();

