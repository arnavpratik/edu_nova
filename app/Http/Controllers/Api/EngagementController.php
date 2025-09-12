<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\EngagementLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB; 

class EngagementController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'lesson_id' => 'required|exists:lessons,id',
            'active_seconds' => 'required|integer',
            'idle_seconds' => 'required|integer',
            'tab_switches' => 'required|integer',
        ]);

        EngagementLog::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'lesson_id' => $validated['lesson_id'],
                'log_date' => Carbon::today(),
            ],
            [
               
                'active_seconds' => DB::raw('active_seconds + ' . $validated['active_seconds']),
                'idle_seconds' => DB::raw('idle_seconds + ' . $validated['idle_seconds']),
                'tab_switches' => DB::raw('tab_switches + ' . $validated['tab_switches']),
            ]
        );
        
        return response()->json(['message' => 'Engagement logged.']);
    }
}