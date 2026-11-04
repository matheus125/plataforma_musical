<?php

namespace App\Http\Controllers\Api;

use App\Actions\Performances\PublishPerformanceAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePerformanceRequest;
use App\Http\Resources\PerformanceResource;
use App\Models\Performance;
use App\Models\Song;
use App\Support\ApiResponse;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PerformanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $performances = Performance::query()
            ->with(['song', 'user', 'mediaAsset'])
            ->where('is_public', true)
            ->latest()
            ->paginate($request->integer('per_page', 15));

        return ApiResponse::success(PerformanceResource::collection($performances));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePerformanceRequest $request, PublishPerformanceAction $publishPerformance)
    {
        $song = Song::query()
            ->whereKey($request->integer('song_id'))
            ->where('is_active', true)
            ->first();

        if (! $song) {
            return ApiResponse::error('Musica indisponivel para envio de performance.', 422, [
                'song_id' => ['A musica selecionada nao existe ou esta inativa.'],
            ]);
        }

        $performance = $publishPerformance->execute(
            user: $request->user(),
            song: $song,
            audio: $request->file('audio'),
            durationSeconds: $request->integer('duration_seconds') ?: null,
            isPublic: $request->boolean('is_public', true),
        )->load(['song', 'user', 'mediaAsset']);

        return ApiResponse::success(new PerformanceResource($performance), 'Performance publicada com sucesso.', 201);
    }

    public function show(Performance $performance)
    {
        if (! $performance->is_public && request()->user()?->id !== $performance->user_id) {
            abort(404);
        }

        return ApiResponse::success(new PerformanceResource($performance->load(['song', 'user', 'mediaAsset', 'latestVocalAnalysis'])));
    }

    public function userIndex(Request $request, int $id)
    {
        $performances = Performance::query()
            ->with(['song', 'user', 'mediaAsset'])
            ->where('user_id', $id)
            ->where('is_public', true)
            ->latest()
            ->paginate($request->integer('per_page', 15));

        return ApiResponse::success(PerformanceResource::collection($performances));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Performance $performance)
    {
        if ($performance->user_id !== $request->user()->id) {
            throw new AuthorizationException('Voce nao pode excluir esta performance.');
        }

        $storagePath = str($performance->audio_url)->after('/storage/')->toString();

        if ($storagePath && ! $performance->media_asset_id) {
            Storage::disk('public')->delete($storagePath);
        }

        if ($performance->mediaAsset) {
            Storage::disk($performance->mediaAsset->disk)->delete($performance->mediaAsset->storage_key);
            $performance->mediaAsset->delete();
        }

        $performance->delete();

        return ApiResponse::success(null, 'Performance removida com sucesso.');
    }
}
