<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static \Illuminate\Database\Eloquent\Collection findMany(array $ids)
 * @method static self create(array $attributes)
 */
class OmdbRecord extends Model
{
    protected $fillable = [
        'id',
        'title',
        'type',
        'year',
    ];

    protected $keyType = 'string';

    public $incrementing = false;

    public function posters()
    {
        return $this->hasMany(Poster::class);
    }
}
