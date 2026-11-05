<?php
namespace App\Http\Controllers;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
class ClientController extends Controller {
 public function index(Request $r){return $r->user()->clients()->when($r->search,fn($q,$s)=>$q->where(fn($w)=>$w->where('name','like',"%$s%")->orWhere('document','like',"%$s%")->orWhere('email','like',"%$s%")))->latest()->paginate(10);}
 public function store(Request $r){$d=$r->validate(['name'=>'required|string|max:160','document'=>['nullable','string','max:30',Rule::unique('clients')->where(fn($q)=>$q->where('user_id',$r->user()->id))],'email'=>'nullable|email|max:180','phone'=>'nullable|string|max:30','notes'=>'nullable|string|max:2000']);return response()->json($r->user()->clients()->create($d),201);}
 public function show(Request $r,Client $client){$this->authorizeOwner($r,$client);return $client;}
 public function update(Request $r,Client $client){$this->authorizeOwner($r,$client);$d=$r->validate(['name'=>'sometimes|required|string|max:160','document'=>['nullable','string','max:30',Rule::unique('clients')->ignore($client->id)->where(fn($q)=>$q->where('user_id',$r->user()->id))],'email'=>'nullable|email|max:180','phone'=>'nullable|string|max:30','notes'=>'nullable|string|max:2000']);$client->update($d);return $client->fresh();}
 public function destroy(Request $r,Client $client){$this->authorizeOwner($r,$client);$client->delete();return response()->noContent();}
 private function authorizeOwner($r,$client){abort_unless($client->user_id===$r->user()->id,404);}
}
