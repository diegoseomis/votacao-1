<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Vote extends Model {
    protected $fillable = ['exhibition_id', 'artwork_id', 'voter_name'];
}
