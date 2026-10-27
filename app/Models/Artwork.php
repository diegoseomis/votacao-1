<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Artwork extends Model {
    protected $fillable = ['title', 'artist', 'description', 'image_path'];
    public function exhibition() { return $this->belongsTo(Exhibition::class); }
    public function votes() { return $this->hasMany(Vote::class); }
}
