<?php
use Illuminate\Database\Migrations\Migration;use Illuminate\Database\Schema\Blueprint;use Illuminate\Support\Facades\Schema;
return new class extends Migration {public function up():void{Schema::create('clients',function(Blueprint $t){$t->id();$t->foreignId('user_id')->constrained()->cascadeOnDelete();$t->string('name');$t->string('document',30)->nullable();$t->string('email')->nullable();$t->string('phone',30)->nullable();$t->text('notes')->nullable();$t->timestamps();$t->index(['user_id','name']);});}public function down():void{Schema::dropIfExists('clients');}};
