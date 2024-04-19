<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoriesController extends CrudController
{
    const MODEL = Category::class;
    const SEARCH_QUERY_FIELD_NAME = 'title';
}
