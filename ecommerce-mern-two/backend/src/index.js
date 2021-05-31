require('dotenv').config();
const express = require("express");
const app = express();
const cookieParser = require("cookie-parser");
const cors = require('cors');
const connection = require('./database/Connection');
const path = require('path');

const { ComentsRoutes, UserRoutes,LikesRoutes, FavoriteRoutes } = require('./routes');
const { apiUser, apiComents, apiLikes, apiFavorites } = require('./config/EndPoints');

// Settings
app.set('PORT', process.env.PORT || 3600);
connection.startConnection();

// Middlewares
app.use( express.json() );
app.use( express.urlencoded({ extended: true }));
app.use(cors( { origin: 'http://localhost:3000' } ));
app.use(cookieParser());

// Routes
app.use( apiUser, UserRoutes );
app.use( apiComents, ComentsRoutes );
app.use( apiFavorites ,FavoriteRoutes );
app.use( apiLikes, LikesRoutes );

// Static Files
app.use('/uploads', express.static(path.resolve('uploads')));

// Starting server
app.listen(app.get('PORT'), () => {
    console.log(`Server started on port ${ app.get('PORT') }`);
});