const ProductModel = require('../model/ProductModel');
const { Types } = require('mongoose');

const getProducts = (request, response) => {
    ProductModel.find({})
    .then((products) => {
        return response.status(200).send({ status: 200, data: products });
    })
    .catch((error) => {
        return response.status(500).send({ status: 404, data: error });
    });
}

const getProduct = async (request, response) => {
    const { productId } = request.params;
    ProductModel.find({ _id: Types.ObjectId(productId) })
    .then((product) => {
        return response.status(200).send({ status: 200, data: product });
    })
    .catch((error) => {
        return response.status(500).send({ status: 404, data: error });
    });
}

module.exports = { 
    getProducts: getProducts,
    getProduct: getProduct
};