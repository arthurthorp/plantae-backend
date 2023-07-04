<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class History extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'description',
        'is_impediment',
        'image_path',
        'activity_id'
    ];

    public function getImagePath()
    {
        if($this->image_path)
            $this->image_path = asset('storage/'.$this->image_path);

        return $this;
    }

    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class);
    }
}
