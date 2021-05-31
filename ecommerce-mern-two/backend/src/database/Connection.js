const mongoose = require('mongoose');

class Connection {
    startConnection () {
        mongoose.connect(`mongodb://${ process.env.HOST}:${ process.env.MONGO_PORT }/${ process.env.DB }`,{
            useCreateIndex: true,
            useUnifiedTopology: true,
            useNewUrlParser: true
        })
        .then((success) => {
            if ( success ) {
                console.log('Mongo DB is connected!');
            }
        })
        .catch((error) => {
            console.log(`Mongo Connection Failed: ${ error }`);
        });
    }
}

module.exports = new Connection;