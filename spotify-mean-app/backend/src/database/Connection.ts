import { connect } from 'mongoose';

export class Connection {
    private dataBase: string = 'PhotoGallery';
    private port: number = 27017;
    private host: string = 'localhost';

    public async connectMe (): Promise<any> {
        try {
            await connect(`mongodb://${ this.host }:${ this.port }/${ this.dataBase }`, {
                useNewUrlParser: true,
                useUnifiedTopology: true,
                useCreateIndex: true
            });
            console.log('MongoDB is Connected!');
        } catch (error) {
            console.log(error);
        }
    }
}