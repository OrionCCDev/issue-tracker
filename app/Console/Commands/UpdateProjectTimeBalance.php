<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Project;
use Carbon\Carbon;

class UpdateProjectTimeBalance extends Command
{
    protected $signature = 'projects:update-time-balance';
    protected $description = 'Update time balance for all projects based on completion date';

    public function handle()
    {
        $projects = Project::whereNotNull('completion_date')->get();
        $today = Carbon::today();

        foreach ($projects as $project) {
            $completionDate = Carbon::parse($project->completion_date);
            $timeBalance = $completionDate->diffInDays($today, false);

            $project->time_balance = $timeBalance;
            $project->save();
        }

        $this->info('Project time balances updated successfully.');
    }
}
