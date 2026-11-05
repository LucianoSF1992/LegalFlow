<?php
namespace App\Http\Controllers;
use App\Models\LegalCase;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
class LegalCaseController extends Controller {
 public function index(Request $r){return LegalCase::with('client:id,name')->where('user_id',$r->user()->id)->when($r->search,fn($q,$s)=>$q->where(fn($w)=>$w->where('title','like',"%$s%")->orWhere('case_number','like',"%$s%")))->when($r->status,fn($q,$s)=>$q->where('status',$s))->latest()->paginate(10);}
 public function store(Request $r){$d=$r->validate(['client_id'=>'required|integer|exists:clients,id','case_number'=>'required|string|max:80','title'=>'required|string|max:180','court'=>'nullable|string|max:120','legal_area'=>'required|string|max:80','status'=>['required',Rule::in(['active','pending','closed'])],'description'=>'nullable|string|max:5000','opened_at'=>'nullable|date']);$this->ensureClient($r,$d['client_id']);$case=LegalCase::create([...$d,'user_id'=>$r->user()->id]);return response()->json($case->load('client:id,name'),201);}
 public function show(Request $r,LegalCase $case){$this->owner($r,$case);return $case->load(['client:id,name','movements']);}
 public function update(Request $r,LegalCase $case){$this->owner($r,$case);$d=$r->validate(['client_id'=>'sometimes|required|integer|exists:clients,id','case_number'=>'sometimes|required|string|max:80','title'=>'sometimes|required|string|max:180','court'=>'nullable|string|max:120','legal_area'=>'sometimes|required|string|max:80','status'=>['sometimes',Rule::in(['active','pending','closed'])],'description'=>'nullable|string|max:5000','opened_at'=>'nullable|date']);if(isset($d['client_id']))$this->ensureClient($r,$d['client_id']);$case->update($d);return $case->fresh()->load('client:id,name');}
 public function destroy(Request $r,LegalCase $case){$this->owner($r,$case);$case->delete();return response()->noContent();}
 private function owner($r,$case){abort_unless($case->user_id===$r->user()->id,404);}
 private function ensureClient($r,$id){abort_unless($r->user()->clients()->whereKey($id)->exists(),422,'Cliente inválido.');}
}
