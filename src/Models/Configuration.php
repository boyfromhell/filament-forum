<?php

namespace IchBin\FilamentForum\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Configuration extends Model
{
    use HasFactory;

    protected $table = 'forum_configurations';

    protected $fillable = [
        'name', 'is_enabled'
    ];

    protected $casts = [
        'is_enabled' => 'bool'
    ];
}
