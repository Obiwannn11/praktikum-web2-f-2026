<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBorrowRequest;
use App\Http\Requests\UpdateBorrowRequest;
use App\Http\Resources\BorrowResource;
use App\Models\Borrow;

class BorrowController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // with(['book', 'user']): muat relasi buku dan user sekaligus
        $borrow = Borrow::with(['book', 'user'])->get();

        return response()->json([
            'status' => 'success',
            'data' => BorrowResource::collection($borrow),
            'message' => 'Berhasil Mengambil Semua Borrow',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBorrowRequest $request)
    {
        $borrow = Borrow::create($request->validated());

        return response()->json([
            'status' => 'success',
            'data' => new BorrowResource($borrow->load(['book', 'user'])),
            'message' => 'Berhasil Menambahkan Borrow',
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Borrow $borrow)
    {
        return response()->json([
            'status' => 'success',
            'data' => new BorrowResource($borrow->load(['book', 'user'])),
            'message' => 'Berhasil Mengambil Detail Borrow',
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBorrowRequest $request, Borrow $borrow)
    {
        $borrow->update($request->validated());

        return response()->json([
            'status' => 'success',
            'data' => new BorrowResource($borrow->load(['book', 'user'])),
            'message' => 'Berhasil Memperbarui Borrow',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Borrow $borrow)
    {
        $borrow->delete();

        return response()->json([
            'status' => 'success',
            'data' => null,
            'message' => 'Berhasil Menghapus Borrow',
        ]);
    }
}
