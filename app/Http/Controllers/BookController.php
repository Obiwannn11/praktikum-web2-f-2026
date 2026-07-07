<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Http\Resources\BookResource;
use App\Models\Book;

class BookController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // with('category'): eager loading agar data kategori ikut terambil (hindari N+1 query)
        $book = Book::with('category')->get();

        return response()->json([
            'status' => 'success',
            'data' => BookResource::collection($book),
            'message' => 'Berhasil Mengambil Semua Book',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookRequest $request)
    {
        $book = Book::create($request->validated());

        return response()->json([
            'status' => 'success',
            'data' => new BookResource($book->load('category')),
            'message' => 'Berhasil Menambahkan Book',
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        return response()->json([
            'status' => 'success',
            'data' => new BookResource($book->load('category')),
            'message' => 'Berhasil Mengambil Detail Book',
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookRequest $request, Book $book)
    {
        $book->update($request->validated());

        return response()->json([
            'status' => 'success',
            'data' => new BookResource($book->load('category')),
            'message' => 'Berhasil Memperbarui Book',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        $book->delete();

        return response()->json([
            'status' => 'success',
            'data' => null,
            'message' => 'Berhasil Menghapus Book',
        ]);
    }
}
