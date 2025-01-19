<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class MusicList extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    public $table = "music_list";

    protected $fillable = [
        'title',
        'artist',
        'album',
        'genre',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('music')->singleFile();
        $this->addMediaCollection('thumbnails')->singleFile();
    }
}
