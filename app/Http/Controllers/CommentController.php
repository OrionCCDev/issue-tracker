<?php
namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Issue;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\NotificationService;

class CommentController extends Controller
{
    public function store(Request $request, Project $project, Issue $issue)
    {
        $request->validate([
            'description' => 'required|string'
        ]);

        $comment = $issue->comments()->create([
            'description' => $request->description,
            'user_id' => Auth::id()
        ]);

        $comment->load('user');

        if ($request->ajax()) {
            return view('issues.comment-item', compact('comment'));
        }

        return redirect()->back()->with('success', 'Comment added successfully');
    }

    public function edit(Project $project, Issue $issue, Comment $comment)
    {
        // Ensure the comment belongs to the issue
        if ($comment->issue_id !== $issue->id) {
            abort(404);
        }

        return view('comments.edit', compact('project', 'issue', 'comment'));
    }

    public function update(Request $request, Project $project, Issue $issue, Comment $comment)
    {
        $this->authorize('update', $comment);

        $request->validate([
            'description' => 'required|string'
        ]);

        $comment->update([
            'description' => $request->description
        ]);

        if ($request->ajax()) {
            return response()->json([
                'content' => $comment->description
            ]);
        }

        return redirect()->back()->with('success', 'Comment updated successfully');
    }

    public function destroy(Request $request, Project $project, Issue $issue, Comment $comment)
    {
        $this->authorize('delete', $comment);

        $comment->delete();

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Comment deleted successfully');
    }
}
