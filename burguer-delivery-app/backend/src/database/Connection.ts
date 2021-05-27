import { connect } from 'mongoose';

export class Connection {
    private dataBase: string;
    private port: number | string = 27017;
    private host: string;


    public constructor () {
        this.dataBase = process.env.DB || 'BurguerDelivery';
        this.port = process.env.MONGO_PORT || 27017;
        this.host = process.env.HOST || 'localhost';
    }

    public connection = async (): Promise<void> => {
        try {
            await connect(`mongodb://${ this.host }:${ this.port }/${ this.dataBase }`, {
                useNewUrlParser: true,
                useUnifiedTopology: true,
                useCreateIndex: true
            });
            console.log('MongoDB is Connected');
        } catch (err) {
            console.log(err);
        }
    }
}