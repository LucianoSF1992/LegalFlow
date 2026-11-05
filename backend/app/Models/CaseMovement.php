<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class CaseMovement extends Model {
 protected $fillable=['case_id','description','occurred_at'];
 protected function casts(): array {return ['occurred_at'=>'datetime'];}
}
