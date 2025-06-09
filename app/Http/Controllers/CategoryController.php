<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::query();

        if ($request->has('search') && !empty($request->search)) {
            $query->where('name', 'LIKE', '%' . $request->search . '%')
                ->orWhere('isBukti', 'LIKE', '%' . $request->search . '%');
        }

        $category = $query->orderBy('id')->cursorPaginate($request->limit ?? 10);


        return response()->json([
            'statusCode' => 200,
            'msg' => 'data category',
            'data' => $category
        ], 200);
    }

    public function show($id)
    {
        $category = Category::find($id);
        return response()->json([
            'statusCode' => 200,
            'msg' => 'detail category',
            'data' => $category
        ], 200);
    }

    public function search($search)
    {
        $category = Category::where(function ($q) use ($search) {
            $q->where('name', 'lile', "%{$search}%");
        })->orderBy('created_at', 'DESC')
            ->orderBy('id', 'ASC')
            ->cursorPaginate(10);

        if ($category->isEmpty()) {
            return response()->json([
                'statusCode' => 200,
                'msg' => 'data tidak ditemukan',
                'data' => []
            ], 200);
        }
        return response()->json([
            'statusCode' => 200,
            'msg' => 'data berhasil ditemukan',
            'data' => $category
        ], 200);
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required',
            'isBukti' => 'required|in:ya,tidak'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'msg' => $validate->errors(),
                'data' => []
            ], 200);
        }

        $input = $validate->validate();

        $category = Category::create($input);

        return response()->json([
            'statusCode' => 200,
            'msg' => 'data berhasil disimpan',
            'data' => $category
        ], 200);
    }

    public function update(Request $request,  $id)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required',
            'isBukti' => 'required|in:ya,tidak'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'statusCode' => 422,
                'msg' => $validate->errors(),
                'data' => []
            ], 422);
        }

        $category = Category::find($id);
        $input = $request->all();

        $category->update($input);

        return response()->json([
            'statusCode' => 200,
            'msg' => 'data berhasil diubah',
            'data' => $category
        ], 200);
    }

    public function destroy($id)
    {
        $category = Category::find($id);
        $category->delete();

        return response()->json([
            'statusCode' => 200,
            'msg' => 'data berhasil dihapus',
            'data' => $category
        ], 200);
    }
}
