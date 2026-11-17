<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Http\Resources\BookResource;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index(Request $request)
    {
        return BookResource::collection(
            $request->user()->books()->latest()->paginate(10)
        );
    }

    public function show(Request $request, $id)
    {
        return new BookResource($request->user()->books()->findOrFail($id));
    }

    public function store(StoreBookRequest $request)
    {
        $book = $request->user()->books()->create($request->validated());

        return (new BookResource($book))->response()->setStatusCode(201);
    }

    public function update(UpdateBookRequest $request, $id)
    {
        $book = $request->user()->books()->findOrFail($id);
        $book->update($request->validated());

        return new BookResource($book);
    }

    public function destroy(Request $request, $id)
    {
        $request->user()->books()->findOrFail($id)->delete();

        return response()->json(['message' => 'Book deleted.']);
    }
}