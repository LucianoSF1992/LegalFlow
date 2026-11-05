<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Client extends Model {
 protected $fillable=['user_id','name','document','email','phone','notes'];
 public function user(){return $this->belongsTo(User::class);}
 public function cases(){return $this->hasMany(LegalCase::class,'client_id');}
}
