<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class KnowledgeDocument extends Model
{
    use HasFactory, Searchable;

    protected $fillable = ['title', 'content_chunk', 'source_type', 'source_path', 'category', 'is_active'];
    protected $casts = ['is_active' => 'boolean'];

    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content_chunk' => $this->content_chunk,
            'category' => $this->category,
        ];
    }
}
