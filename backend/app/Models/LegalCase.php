<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class LegalCase extends Model {
 protected $table='cases';
 protected $fillable=['user_id','client_id','case_number','title','court','legal_area','status','description','opened_at'];
 protected function casts(): array {return ['opened_at'=>'date'];}
 public function client(){return $this->belongsTo(Client::class);}
 public function user(){return $this->belongsTo(User::class);}
 public function movements(){return $this->hasMany(CaseMovement::class,'case_id')->latest();}
}
