<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property string $author
 * @property int $year
 * @property string $genre
 * @property int $pages
 * @property string $language
 * @property int $quantity
 * @property int $reservations_count
 * @property mixed $reservations
 */

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'author',
        'year',
        'genre',
        'pages',
        'language',
        'quantity'
    ];

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }
}
