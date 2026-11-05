<?php
namespace App\Http\Controllers;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
class TaskController extends Controller {
 public function index(Request $r){return Task::with('legalCase:id,title')->where('user_id',$r->user()->id)->when($r->status,fn($q,$s)=>$q->where('status',$s))->orderByRaw('due_date is null, due_date asc')->paginate(10);}
 public function store(Request $r){$d=$r->validate(['case_id'=>'nullable|integer|exists:cases,id','title'=>'required|string|max:180','description'=>'nullable|string|max:2000','priority'=>['required',Rule::in(['low','medium','high'])],'status'=>['required',Rule::in(['pending','in_progress','completed'])],'due_date'=>'nullable|date']);if(isset($d['case_id']))$this->ensureCase($r,$d['case_id']);return response()->json(Task::create([...$d,'user_id'=>$r->user()->id]),201);}
 public function show(Request $r,Task $task){$this->owner($r,$task);return $task->load('legalCase:id,title');}
 public function update(Request $r,Task $task){$this->owner($r,$task);$d=$r->validate(['case_id'=>'nullable|integer|exists:cases,id','title'=>'sometimes|required|string|max:180','description'=>'nullable|string|max:2000','priority'=>[Rule::in(['low','medium','high'])],'status'=>[Rule::in(['pending','in_progress','completed'])],'due_date'=>'nullable|date']);if(isset($d['case_id']))$this->ensureCase($r,$d['case_id']);$task->update($d);return $task->fresh();}
 public function destroy(Request $r,Task $task){$this->owner($r,$task);$task->delete();return response()->noContent();}
 private function owner($r,$task){abort_unless($task->user_id===$r->user()->id,404);}
 private function ensureCase($r,$id){abort_unless($r->user()->id===\App\Models\LegalCase::whereKey($id)->value('user_id'),422,'Processo inválido.');}
}
