<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductsController extends CrudController
{
    const MODEL = Product::class;
    const SEARCH_QUERY_FIELD_NAME = 'name';
}
