<?php
namespace App\Http\Controllers;

use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Spatie\GoogleCalendar\Event;

class TaskController extends Controller
{
    /**
     *
     */
    public function authorize($ability, $arguments = [])
    {
        if (Auth::check()) {
            return Auth::user()->can($ability, $arguments);
        }

        return false;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user  = Auth::user();
        $tasks = $user->tasks();

        if ($request->has('priority') && in_array($request->priority, ['low', 'medium', 'high'])) {
            $tasks->where('priority', $request->priority);
        }

        if ($request->has('status') && in_array($request->status, ['to-do', 'in progress', 'done'])) {
            $tasks->where('status', $request->status);
        }

        if ($request->has('due_date')) {
            try {
                $dueDate = Carbon::parse($request->due_date);
                $tasks->whereDate('due_date', $dueDate);
            } catch (\Exception $e) {
            }
        }

        $tasks = $tasks->orderBy('due_date')->paginate(10);

        return view('tasks.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tasks.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'name'                   => 'required|string|max:255',
            'description'            => 'nullable|string',
            'priority'               => 'required|in:low,medium,high',
            'status'                 => 'required|in:to-do,in progress,done',
            'due_date'               => 'required|date|after_or_equal:today',
            // 'add_to_google_calendar' => 'boolean',
        ]);

        $task = Auth::user()->tasks()->create($validatedData);

        // if (isset($validatedData['add_to_google_calendar']) && $validatedData['add_to_google_calendar']) {
        //     try {
        //         $event = Event::create([
        //             'name'          => $task->name,
        //             'description'   => $task->description,
        //             'startDateTime' => Carbon::parse($task->due_date)->startOfDay(),
        //             'endDateTime'   => Carbon::parse($task->due_date)->endOfDay(),
        //             'location'      => 'To-Do App',
        //         ]);
        //         $task->google_calendar_event_id = $event->id;
        //         $task->save();
        //         session()->flash('success', 'Task created and added to Google Calendar successfully!');
        //     } catch (\Exception $e) {
        //         session()->flash('warning', 'Task created but failed to add to Google Calendar. Please check your Google Calendar settings or try again later. Error: ' . $e->getMessage());
        //     }
        // }

        return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        $this->authorize('view', $task);

        return view('tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        $this->authorize('update', $task);

        return view('tasks.edit', compact('task'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        $validatedData = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority'    => 'required|in:low,medium,high',
            'status'      => 'required|in:to-do,in progress,done',
            'due_date'    => 'required|date|after_or_equal:today',
            // 'add_to_google_calendar' => 'boolean',
        ]);

        $task->update($validatedData);

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');


        // Google Calendar
        // if (isset($validatedData['add_to_google_calendar']) && $validatedData['add_to_google_calendar']) {
        //     try {
        //         if ($task->google_calendar_event_id) {
        //             $event = Event::find($task->google_calendar_event_id);
        //             if ($event) {
        //                 $event->name          = $task->name;
        //                 $event->description   = $task->description;
        //                 $event->startDateTime = Carbon::parse($task->due_date)->startOfDay();
        //                 $event->endDateTime   = Carbon::parse($task->due_date)->endOfDay();
        //                 $event->save();
        //                 session()->flash('success', '');
        //             }
        //         } else {
        //             // Stwórz nowe wydarzenie
        //             $event = Event::create([
        //                 'name'          => $task->name,
        //                 'description'   => $task->description,
        //                 'startDateTime' => Carbon::parse($task->due_date)->startOfDay(),
        //                 'endDateTime'   => Carbon::parse($task->due_date)->endOfDay(),
        //                 'location'      => 'To-Do App',
        //             ]);
        //             $task->google_calendar_event_id = $event->id;
        //             $task->save();
        //             session()->flash('success', '');
        //         }
        //     } catch (\Exception $e) {
        //         session()->flash('warning', '$e->getMessage());
        //     }
        // } elseif (! $validatedData['add_to_google_calendar'] && $task->google_calendar_event_id) {
        //     // Usuń z Google Calendar, jeśli odznaczono
        //     try {
        //         $event = Event::find($task->google_calendar_event_id);
        //         if ($event) {
        //             $event->delete();
        //             $task->google_calendar_event_id = null;
        //             $task->save();
        //             session()->flash('success', '');
        //         }
        //     } catch (\Exception $e) {
        //         session()->flash('warning', $e->getMessage());
        //     }
        // }

        // return redirect()->route('tasks.index')->with('success', '');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);

        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully.');
    }

    /**
     * Generate a shareable link for a task.
     */
    public function generateShareLink(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        $token     = Str::random(32);
        $expiresAt = Carbon::now()->addHours(24);

        $task->update([
            'access_token'     => $token,
            'token_expires_at' => $expiresAt,
        ]);

        $shareLink = route('tasks.share', ['token' => $token]);

        return back()->with('share_link', $shareLink)->with('success', 'Share link generated successfully.');
    }

    /**
     * Display a shared task.
     */
    public function showSharedTask(Request $request, string $token)
    {
        $task = Task::where('access_token', $token)
            ->where('token_expires_at', '>', Carbon::now())
            ->first();

        if (! $task) {
            abort(404, 'Task not found or link expired.');
        }

        return view('tasks.shared', compact('task'));
    }
}
