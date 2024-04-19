<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaginationRequest;
use Illuminate\Http\Request;

abstract class CrudController extends Controller
{
    const MODEL = null;
    const SEARCH_QUERY_FIELD_NAME = null;

    public function getAll(PaginationRequest $request)
    {
        $data = $request->validated();

        $query = static::MODEL::query();
        if (array_key_exists('q', $data) && is_string($data['q'])) {
            $q = strtolower($data['q']);
            $query = $query->whereRaw('LOWER(' . static::SEARCH_QUERY_FIELD_NAME . ') like ?', ['%' . $q . '%']);
        }

        if (array_key_exists('except_ids', $data) && count($data['except_ids']) > 0) {
            $query = $query->whereNotIn('id', $data['except_ids']);
        }

        $offset = array_key_exists('offset', $data) && isset($data['offset']) && is_numeric($data['offset']) ? intval($data['offset']) : 0;
        $limit = array_key_exists('limit', $data) && isset($data['limit']) && is_numeric($data['limit']) ? intval($data['limit']) : 10;
        $query = $query->offset($offset)->limit($limit)->orderBy('id', 'desc');

        return response()->json($query->get());
    }

    public function get($id)
    {
        return response()->json(static::MODEL::findOrFail($id));
    }

    public function create($request)
    {
        $model = new (static::MODEL)($request->validated());
        $model->save();
        return response()->json($model);
    }

    public function edit($id, $request)
    {
        $model = static::MODEL::findOrFail($id);
        $data = $request->validated();
        foreach (array_keys($data) as $key) {
            $model->$key = $data[$key];
        }
        $model->save();
        return response()->json($model);
    }

    public function delete($id)
    {
        static::MODEL::where('id', $id)->delete();
        return response()->json(null);
    }
}
