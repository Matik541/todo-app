<?php

namespace App\Observers;

use App\Models\Task;
use App\Models\TaskHistory;
use Illuminate\Support\Facades\Auth;

class TaskObserver
{
    /**
     * Handle the Task "created" event.
     */
    public function created(Task $task): void
    {
        //
    }

    /**
     * Handle the Task "updated" event.
     */
    public function updated(Task $task): void
    {
        $changedAttributes = $task->getDirty(); 
        $originalAttributes = $task->getOriginal(); 

        $relevantAttributes = ['name', 'description', 'priority', 'status', 'due_date'];
        $changesToRecord = [];

        foreach ($relevantAttributes as $attribute) {
            if (isset($changedAttributes[$attribute]) && $changedAttributes[$attribute] != $originalAttributes[$attribute]) {
                $changesToRecord[$attribute] = [
                    'old' => $originalAttributes[$attribute] ?? null,
                    'new' => $changedAttributes[$attribute] ?? null,
                ];
            }
        }

        if (!empty($changesToRecord)) {
            $changeSummary = "Changes: ";
            $parts = [];
            foreach ($changesToRecord as $attribute => $values) {
                $parts[] = "{$attribute} from '" . ($values['old'] ?? 'brak') . "' to '" . ($values['new'] ?? 'brak') . "'";
            }
            $changeSummary .= implode(", ", $parts) . ".";

            TaskHistory::create([
                'task_id' => $task->id,
                'user_id' => Auth::id(),
                'old_data' => $task->getOriginal(),
                'new_data' => $task->fresh()->toArray(), 
                'change_summary' => $changeSummary,
            ]);
        }
    }

    /**
     * Handle the Task "deleted" event.
     */
    public function deleted(Task $task): void
    {
        //
    }

    /**
     * Handle the Task "restored" event.
     */
    public function restored(Task $task): void
    {
        //
    }

    /**
     * Handle the Task "force deleted" event.
     */
    public function forceDeleted(Task $task): void
    {
        //
    }
}
