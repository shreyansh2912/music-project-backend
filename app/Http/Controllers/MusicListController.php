<?php

namespace App\Http\Controllers;

use App\Http\Requests\MusicList\storeRequest;
use App\Models\MusicList;
use Illuminate\Http\Request;

class MusicListController extends Controller
{

    public function index(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 10);

            $musicCollection = MusicList::orderByDesc('id')->paginate($perPage);

            $musicCollection->getCollection()->transform(function ($music) {
                $music->music_url = $music->getFirstMediaUrl('music');

                $music->thumbnail_url = $music->getFirstMediaUrl('thumbnails');

                return $music;
            });

            return response()->successJson([],[
                'data' => $musicCollection->items(),
                'current_page' => $musicCollection->currentPage(),
                'last_page' => $musicCollection->lastPage(),
                'total' => $musicCollection->total(),
            ], 200);
        } catch (\Throwable $th) {
            return response()->errorJson([], ['message' => "Something went wrong"], 500);
        }
    }

    public function store(storeRequest $request)
    {
        try {
            $music = MusicList::create([
                'title' => $request->title,
                'artist' => $request->artist,
                'album' => $request->album,
                'genre' => $request->genre,
            ]);

            if ($request->hasFile('music_file')) {
                $music->addMedia($request->file('music_file'))->toMediaCollection('music');
            }

            if ($request->hasFile('thumbnail')) {
                $music->addMedia($request->file('thumbnail'))->toMediaCollection('thumbnails');
            }

            return response()->successJson([], ['message' => 'Music uploaded successfully'], 201);
        } catch (\Throwable $th) {
            return response()->errorJson([], ['message' => "Something went wrong"], 500);
        }
    }

    public function edit($id)
    {
        try {
            $music = MusicList::find($id);

            if (!$music) {
                return response()->errorJson([],['message' => 'Music not found'], 404);
            }
            
            return response()->successJson([
                    'id' => $music->id,
                    'title' => $music->title,
                    'artist' => $music->artist,
                    'album' => $music->album,
                    'genre' => $music->genre,
                    'duration' => $music->duration,
                    'music_url' => $music->getFirstMediaUrl('music'),
                    'thumbnail_url' => $music->getFirstMediaUrl('thumbnails'),
                ],[], 200);
        } catch (\Throwable $th) {
            return response()->errorJson([], ['message' => "Something went wrong"], 500);
        }
    }

    public function update(storeRequest $request, $id)
    {
        try {
            $music = MusicList::find($id);

            if (!$music) {
                return response()->errorJson([],['message' => 'Music not found'], 404);
            }

            $music->update($request->all());

            if ($request->hasFile('music')) {
                $music->clearMediaCollection('music');
                $music->addMedia($request->file('music'))->toMediaCollection('music');
            }

            if ($request->hasFile('thumbnail')) {
                $music->clearMediaCollection('thumbnails');
                $music->addMedia($request->file('thumbnail'))->toMediaCollection('thumbnails');
            }

            return response()->successJson([],['message' => 'Music updated successfully'], 200);
        } catch (\Throwable $th) {
            return response()->errorJson([], ['message' => "Something went wrong"], 500);
        }
    }

    public function delete($id)
    {
        try {
            $music = MusicList::find($id);

            if (!$music) {
                return response()->errorJson([],['message' => 'Music not found'], 404);
            }

            $music->clearMediaCollection();
            $music->delete();

            return response()->successJson([],['message' => 'Music deleted successfully'], 200);
        } catch (\Throwable $th) {
            return response()->errorJson([], ['message' => "Something went wrong"], 500);
        }
    }

}
