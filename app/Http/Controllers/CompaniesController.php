<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompaniesController extends CrudController
{
    const MODEL = Company::class;
    const SEARCH_QUERY_FIELD_NAME = 'name';
}
