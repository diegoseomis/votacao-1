<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Exhibition extends Model {
    protected $fillable = ['title', 'description', 'status'];
    public function artworks() { return $this->hasMany(Artwork::class); }
    public function votes() { return $this->hasMany(Vote::class); }
    public function getStatusLabelAttribute(): string {
        return ['draft' => 'Em preparação', 'open' => 'Votação aberta', 'closed' => 'Votação encerrada'][$this->status];
    }
}
