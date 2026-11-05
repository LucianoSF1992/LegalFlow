<?php
use Illuminate\Database\Migrations\Migration;use Illuminate\Database\Schema\Blueprint;use Illuminate\Support\Facades\Schema;
return new class extends Migration {public function up():void{Schema::create('case_movements',function(Blueprint $t){$t->id();$t->foreignId('case_id')->constrained('cases')->cascadeOnDelete();$t->text('description');$t->timestamp('occurred_at')->nullable();$t->timestamps();});}public function down():void{Schema::dropIfExists('case_movements');}};
