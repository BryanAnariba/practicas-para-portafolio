const mongoose = require('mongoose')
class Connection {
    connect () {
        mongoose.connect(`${process.env.MONGO_URI}` ,{
        useNewUrlParser: true,
        useCreateIndex: true,
        useUnifiedTopology: true,
        useFindAndModify: false
        })
        .then(() => {
            console.log('MongoDB is Connected!')
        })
        .catch((error) => {
            console.log(error)
        })
    }
}

module.exports = Connection