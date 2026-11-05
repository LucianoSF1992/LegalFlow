<?php
namespace Tests\Feature;
use Illuminate\Foundation\Testing\RefreshDatabase;use Tests\TestCase;
class AuthApiTest extends TestCase {
 use RefreshDatabase;
 public function test_user_can_register_and_receive_token():void{$this->postJson('/api/auth/register',['name'=>'Ana','email'=>'ana@example.com','password'=>'password123','password_confirmation'=>'password123'])->assertCreated()->assertJsonStructure(['user'=>['id','name','email'],'token']);}
}
