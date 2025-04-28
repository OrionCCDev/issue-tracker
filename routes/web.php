<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\IssueController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\NotificationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Welcome/Landing page
Route::get('/', function () {
    // If user is logged in, redirect to dashboard, otherwise show login page
    return redirect()->route('login');
});

// Authentication Routes (provided by Laravel Breeze)
require __DIR__.'/auth.php';

// Protected routes (require authentication)
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // User Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/issues/{issue}/get', [IssueController::class, 'getIssue'])->name('issues.get');

    // Notifications Routes
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
    Route::post('/notifications/{id}/mark-as-unread', [NotificationController::class, 'markAsUnread'])->name('notifications.mark-as-unread');
    Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-as-read');
    Route::get('/notifications/create-samples', [NotificationController::class, 'createSampleNotifications'])->name('notifications.create-samples');
    Route::get('/notifications/data', [NotificationController::class, 'getNotificationsData'])->name('notifications.data');

    // Users Management (Admin and CM only)
    Route::middleware('role:o-admin|cm')->group(function () {
        Route::resource('users', UserController::class);
        Route::post('users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
    });

    // Projects Creation/Deletion (Admin and CM only)
    Route::middleware('role:o-admin|cm')->group(function () {
        Route::get('/projects/create', [ProjectController::class, 'create'])->name('projects.create');
        Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
        Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');
    });

    // Projects Routes - Accessible to all authenticated users
    Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');
    Route::get('/projects/{project}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
    Route::put('/projects/{project}', [ProjectController::class, 'update'])->name('projects.update');

    // API route for getting project members
    Route::get('/api/projects/{project}/members', [ProjectController::class, 'getProjectMembers']);

    Route::get('projects/{project}/members', [ProjectController::class, 'members'])->name('projects.members');
    Route::post('projects/{project}/members', [ProjectController::class, 'addMember'])->name('projects.members.add');
    Route::delete('projects/{project}/members/{user}', [ProjectController::class, 'removeMember'])->name('projects.members.remove');

    // Projects Issues Cards Routes
    Route::get('projects/{project}/issues-list', [ProjectController::class, 'issuesList'])->name('projects.issues.list');
    Route::get('projects/{project}/issues-cards', [ProjectController::class, 'issuesCards'])->name('projects.issues.cards');
    Route::get('projects/{project}/issues-cards-partial', [ProjectController::class, 'issuesCardsPartial'])->name('projects.issues.cards.partial');

    // Issue Details for Modal
    Route::get('issues/{issue}/details', [IssueController::class, 'getIssueDetails'])->name('issues.details');
    Route::post('issues/{issue}/ajax-update', [IssueController::class, 'ajaxUpdate'])->name('issues.ajax-update');
    Route::get('issues/{issue}/comments', [IssueController::class, 'getComments'])->name('issues.comments');
    Route::get('issues/{issue}/history', [IssueController::class, 'getHistory'])->name('issues.history');

    // Meetings Routes
    Route::get('meetings', [MeetingController::class, 'index'])->name('meetings.index');
    Route::get('projects/{project}/meetings/create', [MeetingController::class, 'create'])->name('meetings.create');
    Route::post('projects/{project}/meetings', [MeetingController::class, 'store'])->name('meetings.store');
    Route::get('meetings/{meeting}', [MeetingController::class, 'show'])->name('meetings.show');
    Route::get('meetings/{meeting}/edit', [MeetingController::class, 'edit'])->name('meetings.edit');
    Route::put('meetings/{meeting}', [MeetingController::class, 'update'])->name('meetings.update');
    Route::put('meetings/{meeting}/status', [MeetingController::class, 'updateStatus'])->name('meetings.update-status');
    Route::put('meetings/{meeting}/issues/{issue}', [MeetingController::class, 'updateIssueStatus'])->name('meetings.update-issue-status');
    Route::post('meetings/{meeting}/project-changes', [MeetingController::class, 'recordProjectChange'])->name('meetings.record-project-change');

    // Standalone Issues Routes (for listing all issues across projects)
    Route::get('/issues/my-issues', [IssueController::class, 'myIssues'])->name('issues.my-issues');
    Route::get('/issues/mark-all-read', [IssueController::class, 'markAllRead'])->name('issues.markAllRead');
    Route::get('/issues/create', [IssueController::class, 'create'])->name('issues.create');

    // Issues Routes (Admin, CM and PM only)
    Route::middleware('role:o-admin|cm|pm')->group(function () {
        Route::get('/issues', [IssueController::class, 'index'])->name('issues.index');
    });

    Route::post('/issues', [IssueController::class, 'store'])->name('issues.store');
    Route::get('/issues/{issue}', [IssueController::class, 'show'])->name('issues.show');
    Route::get('/issues/{issue}/edit', [IssueController::class, 'edit'])->name('issues.edit');
    Route::put('/issues/{issue}', [IssueController::class, 'update'])->name('issues.update');
    Route::delete('/issues/{issue}', [IssueController::class, 'destroy'])->name('issues.destroy');

    // Nested Issues Routes (Project-specific issues)
    Route::get('projects/{project}/issues', [IssueController::class, 'projectIssues'])->name('projects.issues.index');
    Route::get('projects/{project}/issues/create', [IssueController::class, 'createProjectIssue'])->name('projects.issues.create');
    Route::post('projects/{project}/issues', [IssueController::class, 'storeProjectIssue'])->name('projects.issues.store');
    Route::get('projects/{project}/issues/{issue}', [IssueController::class, 'show'])->name('projects.issues.show');
    Route::get('projects/{project}/issues/{issue}/edit', [IssueController::class, 'edit'])->name('projects.issues.edit');
    Route::put('projects/{project}/issues/{issue}', [IssueController::class, 'update'])->name('projects.issues.update');
    Route::delete('projects/{project}/issues/{issue}', [IssueController::class, 'destroy'])->name('projects.issues.destroy');

    // Comments Routes
    Route::post('projects/{project}/issues/{issue}/comments', [CommentController::class, 'store'])->name('projects.issues.comments.store');
    Route::post('projects/{project}/issues/{issue}/comments/{comment}/update', [CommentController::class, 'update'])->name('projects.issues.comments.update');
    Route::post('projects/{project}/issues/{issue}/comments/{comment}/delete', [CommentController::class, 'destroy'])->name('projects.issues.comments.destroy');
});
