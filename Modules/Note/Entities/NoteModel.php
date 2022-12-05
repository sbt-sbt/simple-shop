<?php

namespace Modules\Note\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NoteModel extends Model
{
    use HasFactory;

    protected $fillable = ["title","text"];

    protected static function newFactory()
    {
        return \Modules\Note\Database\factories\NoteModelFactory::new();
    }
}
