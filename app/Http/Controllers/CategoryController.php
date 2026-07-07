<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;

class CategoryController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $category = Category::all();

        return response()->json([
            'status' => 'success',
            'data' => CategoryResource::collection($category),
            'message' => 'Berhasil Mengambil Semua Category',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        // validated(): hanya ambil data yang lolos aturan validasi
        $category = Category::create($request->validated());

        return response()->json([
            'status' => 'success',
            'data' => new CategoryResource($category),
            'message' => 'Berhasil Menambahkan Category',
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        // Route Model Binding: Laravel otomatis cari Category by id, 404 bila tidak ada
        return response()->json([
            'status' => 'success',
            'data' => new CategoryResource($category),
            'message' => 'Berhasil Mengambil Detail Category',
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $category->update($request->validated());

        return response()->json([
            'status' => 'success',
            'data' => new CategoryResource($category),
            'message' => 'Berhasil Memperbarui Category',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return response()->json([
            'status' => 'success',
            'data' => null,
            'message' => 'Berhasil Menghapus Category',
        ]);
    }
}
