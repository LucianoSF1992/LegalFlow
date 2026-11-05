<?php
namespace App\Http\Controllers;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
class DocumentController extends Controller {
 public function index(Request $r){return Document::where('user_id',$r->user()->id)->latest()->paginate(10);}
 public function store(Request $r){$d=$r->validate(['file'=>'required|file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png','case_id'=>'nullable|integer|exists:cases,id','client_id'=>'nullable|integer|exists:clients,id']);if(isset($d['case_id']))abort_unless(\App\Models\LegalCase::where('id',$d['case_id'])->where('user_id',$r->user()->id)->exists(),422);if(isset($d['client_id']))abort_unless($r->user()->clients()->whereKey($d['client_id'])->exists(),422);$file=$r->file('file');$path=$file->store('documents/'.$r->user()->id,'local');$doc=Document::create(['user_id'=>$r->user()->id,'case_id'=>$d['case_id']??null,'client_id'=>$d['client_id']??null,'original_name'=>$file->getClientOriginalName(),'path'=>$path,'mime_type'=>$file->getMimeType(),'size'=>$file->getSize()]);return response()->json($doc,201);}
 public function download(Request $r,Document $document){$this->owner($r,$document);abort_unless(Storage::disk('local')->exists($document->path),404);return Storage::disk('local')->download($document->path,$document->original_name);}
 public function destroy(Request $r,Document $document){$this->owner($r,$document);Storage::disk('local')->delete($document->path);$document->delete();return response()->noContent();}
 private function owner($r,$d){abort_unless($d->user_id===$r->user()->id,404);}
}
