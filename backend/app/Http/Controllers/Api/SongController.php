<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSongRequest;
use App\Http\Requests\UpdateSongRequest;
use App\Http\Resources\SongResource;
use App\Models\Song;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class SongController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $songs = Song::query()
            ->when($request->query('search'), function ($query, string $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('title', 'ilike', "%{$search}%")
                        ->orWhere('artist', 'ilike', "%{$search}%");
                });
            })
            ->where('is_active', true)
            ->latest()
            ->paginate($request->integer('per_page', 15));

        return ApiResponse::success(SongResource::collection($songs));
    }

    public function show(Song $song)
    {
        if (! $song->is_active) {
            abort(404);
        }

        return ApiResponse::success(new SongResource($song));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSongRequest $request)
    {
        $song = Song::create($request->validated());

        return ApiResponse::success(new SongResource($song), 'Musica criada com sucesso.', 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSongRequest $request, Song $song)
    {
        $song->update($request->validated());

        return ApiResponse::success(new SongResource($song->refresh()), 'Musica atualizada com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Song $song)
    {
        $song->delete();

        return ApiResponse::success(null, 'Musica removida com sucesso.');
    }
}
