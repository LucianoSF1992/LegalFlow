<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Task extends Model {
 protected $fillable=['user_id','case_id','title','description','priority','status','due_date'];
 protected function casts(): array {return ['due_date'=>'date'];}
 public function legalCase(){return $this->belongsTo(LegalCase::class,'case_id');}
}
