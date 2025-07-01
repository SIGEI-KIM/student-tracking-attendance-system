<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany; // Import for the relationship

class Book extends Model
{
    use HasFactory; // Useful if you plan to seed data using factories

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'author',
        'isbn',
        'publisher',
        'publication_year',
        'description',
        'cover_image_url',
        'total_copies',
        'available_copies',
    ];

    /**
     * Get the loans associated with the book.
     */
    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }

    // You might also add an accessor to check availability easily
    public function getIsAvailableAttribute(): bool
    {
        return $this->available_copies > 0;
    }
}