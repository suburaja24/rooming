<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Storage;

class Room extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'capacity',
        'photo',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];

    protected $attributes = [
        'photo' => '/icons/door.svg',
    ];

    protected function photo(): Attribute
    {
        return Attribute::make(
            get:fn($value) => $value ? Storage::url($value) : 'icons/door.svg',
        );
    }

    public function booking_list()
    {
        return $this->belongsTo(BookingList::class, 'id', 'user_id');
    }
}
