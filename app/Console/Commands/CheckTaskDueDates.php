<?php

namespace App\Console\Commands;

use App\Models\Task;
use App\Notifications\TaskDueDateReminder;
use Illuminate\Console\Command;
use Carbon\Carbon;

class CheckTaskDueDates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-task-due-dates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks for tasks due in one day and sends reminders.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tomorrow = Carbon::now()->addDay()->format('Y-m-d');

        $tasks = Task::whereDate('due_date', $tomorrow)
                     ->where('status', '!=', 'done') 
                     ->get();

        foreach ($tasks as $task) {
            if ($task->user) {
                $task->user->notify(new TaskDueDateReminder($task));
                $this->info("Sent reminder to user abput task: \"{$task->name}\" (ID: {$task->id})");
            } else {
                $this->warn("User for task ID {$task->id} does not exist. Cannot send reminder.");
            }
        }

        $this->info('All reminders for tasks due tomorrow have been sent successfully.');
    }
}
