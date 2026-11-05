<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Document extends Model {
 protected $fillable=['user_id','case_id','client_id','original_name','path','mime_type','size'];
 public function legalCase(){return $this->belongsTo(LegalCase::class,'case_id');}
}
