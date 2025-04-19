<?php
// database/seeders/IssuesTableSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Issue;
use App\Models\Project;
use App\Models\User;
use Carbon\Carbon;

class IssueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = Project::with('members')->get();
        $statuses = ['open', 'in_progress', 'review', 'resolved', 'closed'];
        $priorities = ['low', 'medium', 'high', 'critical'];

        foreach ($projects as $project) {
            // Generate 5-15 issues per project
            $issueCount = rand(5, 15);

            for ($i = 0; $i < $issueCount; $i++) {
                $status = $statuses[array_rand($statuses)];
                $priority = $priorities[array_rand($priorities)];
                $creator = $project->members->random();
                $assignee = $project->members->random();

                // Set target date between now and 30 days in the future
                $targetDate = Carbon::now()->addDays(rand(1, 30))->format('Y-m-d');

                // Set actual resolution date only if status is resolved or closed
                $actualDate = null;
                if (in_array($status, ['resolved', 'closed'])) {
                    // Set actual date to sometime before today but after creation
                    $actualDate = Carbon::now()->subDays(rand(1, 10))->format('Y-m-d');
                }

                $issue = Issue::create([
                    'title' => $this->getRandomIssueTitle($i),
                    'description' => $this->getRandomIssueDescription(),
                    'priority' => $priority,
                    'status' => $status,
                    'project_id' => $project->id,
                    'created_by' => $creator->id,
                    'assigned_to' => $assignee->id,
                    'target_resolution_date' => $targetDate,
                    'actual_resolution_date' => $actualDate,
                    'notes' => $this->getRandomNotes(),
                ]);

                // Add random comments to each issue (0-5 comments)
                $commentCount = rand(0, 5);
                for ($j = 0; $j < $commentCount; $j++) {
                    $commenter = $project->members->random();
                    $issue->comments()->create([
                        'description' => $this->getRandomComment(),
                        'user_id' => $commenter->id,
                    ]);
                }
            }
        }
    }

    /**
     * Get a random issue title.
     */
    private function getRandomIssueTitle($index): string
    {
        $titles = [
            'Fix login page error',
            'Update dashboard charts',
            'Implement user profile page',
            'Optimize database queries',
            'Add export to PDF feature',
            'Fix responsive layout on mobile',
            'Implement payment gateway',
            'Create email notification system',
            'Fix broken links in navigation',
            'Update documentation',
            'Implement search functionality',
            'Add password reset feature',
            'Create user onboarding flow',
            'Fix image upload issue',
            'Implement dark mode',
            'Add social media sharing',
            'Fix security vulnerability',
            'Update API endpoints',
            'Implement role-based permissions',
            'Create analytics dashboard',
        ];

        if (isset($titles[$index])) {
            return $titles[$index];
        } else {
            return $titles[array_rand($titles)] . ' ' . rand(1, 999);
        }
    }

    /**
     * Get a random issue description.
     */
    private function getRandomIssueDescription(): string
    {
        $descriptions = [
            "Users are reporting that they can't log in using their credentials. Need to investigate the authentication system and fix any issues.",

            "The dashboard charts need to be updated to show the latest data. The current charts are showing outdated information.",

            "We need to implement a user profile page where users can view and edit their personal information, change passwords, and manage notification settings.",

            "The database queries are running slow, especially on the reporting pages. Need to optimize them for better performance.",

            "Clients have requested the ability to export reports to PDF format. We need to implement this feature using a PDF generation library.",

            "The layout breaks on mobile devices. We need to fix the responsive design to ensure it works on all screen sizes.",

            "We need to integrate with a payment gateway to allow users to make payments. This should include handling payment processing and receipts.",

            "Implement an email notification system to alert users about important events in the system, such as completed tasks or new assignments.",

            "Several links in the main navigation are broken and lead to 404 errors. These need to be fixed to ensure proper navigation.",

            "The documentation is outdated and missing information about recent features. It needs to be updated to reflect the current state of the system."
        ];

        return $descriptions[array_rand($descriptions)];
    }

    /**
     * Get random notes.
     */
    private function getRandomNotes(): string
    {
        $notes = [
            "",
            "This is a high priority item for the next sprint.",
            "The client specifically requested this feature.",
            "This is a bug that affects multiple users.",
            "This issue is dependent on the completion of other tasks.",
            "This is a technical debt item that needs to be addressed.",
            "This is a feature that will improve user experience.",
            "This is a security vulnerability that needs immediate attention.",
            "This is a performance improvement that will make the system faster.",
            "This is a UI/UX improvement that will make the system more intuitive.",
        ];

        return $notes[array_rand($notes)];
    }

    /**
     * Get a random comment.
     */
    private function getRandomComment(): string
    {
        $comments = [
            "I've started working on this issue. Will update when I have progress to report.",
            "This seems more complex than initially estimated. I might need some assistance.",
            "I've fixed the issue in my local environment. Creating a pull request soon.",
            "Could someone provide more details about the expected behavior?",
            "This is related to the issue we discussed in the last team meeting.",
            "I've completed the implementation and tested it. Ready for review.",
            "The fix for this issue might affect other parts of the system. We should be careful.",
            "I've been looking into this issue and I think I understand the root cause.",
            "This is now fixed in the development environment. Please test and confirm.",
            "I've documented the solution in the knowledge base for future reference.",
            "This is a duplicate of issue #123. We should close this one.",
            "I'm not sure this is the right approach. Let's discuss alternatives.",
            "The client has approved this implementation. We can proceed.",
            "I've created a new branch for this feature. Feel free to check it out.",
            "This is blocked by issue #456. We need to resolve that first.",
        ];

        return $comments[array_rand($comments)];
    }
}