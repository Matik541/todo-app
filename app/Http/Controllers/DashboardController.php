<?php
namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $tasksForToday = Task::where('user_id', $user->id)
            ->whereDate('due_date', today())
            ->where('status', '!=', 'done')
            ->limit(5)
            ->get();

        $totalTasks           = Task::where('user_id', $user->id)->count();
        $completedTasks       = Task::where('user_id', $user->id)->where('status', 'done')->count();
        $completionPercentage = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;

        $upcomingTasks = Task::where('user_id', $user->id)
            ->where('due_date', '>', now()) 
            ->where('status', '!=', 'done')
            ->orderBy('due_date')
            ->limit(3)
            ->get();

        $tasksThisWeek = Task::where('user_id', $user->id)
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->count();
        $allCompletedTasks = Task::where('user_id', $user->id)
            ->where('status', 'done')
            ->count();

        return view('dashboard', compact(
            'tasksForToday',
            'completionPercentage',
            'upcomingTasks',
            'tasksThisWeek',
            'allCompletedTasks'
        ));
    }
}
