<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Traits\HasSpatial;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use MatanYadaev\EloquentSpatial\SpatialBuilder;

class PetaKoordiant extends Model
{
    use HasFactory, HasSpatial;

    protected $guarded = ['id'];

    protected $casts = [
        'coor' => Point::class,
        // 'area' => Polygon::class,
    ];
}
