<?php
namespace App\Http\Controllers;
use App\Models\Client;
use App\Models\LegalCase;
use App\Models\Task;
use Illuminate\Http\Request;
class DashboardController extends Controller {
 public function __invoke(Request $r){$id=$r->user()->id;return response()->json([
 'clients'=>Client::where('user_id',$id)->count(),
 'active_cases'=>LegalCase::where('user_id',$id)->where('status','active')->count(),
 'pending_cases'=>LegalCase::where('user_id',$id)->where('status','pending')->count(),
 'open_tasks'=>Task::where('user_id',$id)->whereIn('status',['pending','in_progress'])->count(),
 'overdue_tasks'=>Task::where('user_id',$id)->whereIn('status',['pending','in_progress'])->whereDate('due_date','<',now())->count(),
 'recent_cases'=>LegalCase::with('client:id,name')->where('user_id',$id)->latest()->limit(5)->get(),
 'upcoming_tasks'=>Task::where('user_id',$id)->whereIn('status',['pending','in_progress'])->whereNotNull('due_date')->orderBy('due_date')->limit(5)->get()
 ]);}
}
