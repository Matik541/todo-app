<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TaskController extends Controller
{
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
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority'    => 'required|in:low,medium,high',
            'status'      => 'required|in:to-do,in progress,done',
            'due_date'    => 'required|date|after_or_equal:today', 
        ]);
        Auth::user()->tasks()->create($validatedData);

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
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:to-do,in progress,done',
            'due_date' => 'required|date|after_or_equal:today',
        ]);


        $task->update($validatedData);

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
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
}
