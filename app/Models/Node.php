<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Node extends Model
{
    protected $fillable = ['parent_id'];

    public static function boot()
    {
        parent::boot();

        static::creating(function (self $node) {
            $pathIndex = $node->parent
                ? $node->parent->children()->max('path')
                : Node::whereNull('parent_id')->max('path');
            $pathIndex = collect(explode('/', $pathIndex))
                ->filter()
                ->last() + 1;
            $node->path = ($node->parent?->path ?? '/')
                .str_pad($pathIndex, 5, '0', STR_PAD_LEFT).'/';
        });
    }

    public function parent()
    {
        return $this->belongsTo(Node::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Node::class, 'parent_id');
    }
}
