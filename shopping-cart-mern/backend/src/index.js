require('dotenv').config();
const express = require('express');
const cors = require('cors');
const database = require('./config/Connection');
const ProductsRoutes = require('./routes/Products.Routes');

// Settings
const app = express();

// Middlewares
app.use(cors());
app.use(express.json());
app.use(express.urlencoded({ extended: true }));


// Routes
const endpoints = {
    products: '/api/products',
};

app.use( endpoints.products, ProductsRoutes );

// Static files

// Starting server
async function main () {
    try {
        await app.listen(process.env.PORT || 5000);
        console.log(`Server started on port ${ process.env.PORT || 5000 }`);
        const db = new database();
        await db.connect();
    } catch (error) {
        console.log('Error, ', error);
    }
}

main();


