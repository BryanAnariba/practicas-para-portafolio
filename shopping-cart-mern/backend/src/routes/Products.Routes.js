const express = require('express');
const router = express();

const { getProducts, getProduct } = require('../controller/Products');

// Get all products
router.get('/', getProducts);

// Get one product
router.get('/:productId', getProduct);

module.exports = router;