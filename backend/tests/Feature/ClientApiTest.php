<?php
namespace Tests\Feature;
use App\Models\User;use Illuminate\Foundation\Testing\RefreshDatabase;use Tests\TestCase;
class ClientApiTest extends TestCase {
 use RefreshDatabase;
 public function test_authenticated_user_can_create_and_list_own_clients():void{
  $user=User::create(['name'=>'Teste','email'=>'teste@example.com','password'=>'password123']);
  $this->actingAs($user,'sanctum')->postJson('/api/clients',['name'=>'Cliente Teste','email'=>'cliente@example.com'])->assertCreated()->assertJsonPath('name','Cliente Teste');
  $this->actingAs($user,'sanctum')->getJson('/api/clients')->assertOk()->assertJsonFragment(['name'=>'Cliente Teste']);
 }
 public function test_clients_endpoint_requires_authentication():void{$this->getJson('/api/clients')->assertUnauthorized();}
}
