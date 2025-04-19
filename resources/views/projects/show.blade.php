@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card mainCard" style="background-color: #45bddb;">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ $project->name }} ({{ $project->code }})</span>
                    <div>
                        <a href="{{ route('projects.edit', $project) }}" class="btn btn-primary btn-sm">Edit Project</a>
                        <a href="{{ route('projects.index') }}" class="btn btn-secondary btn-sm">Back to List</a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Issues Statistics -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">Issues Overview</h5>
                                    <button class="btn btn-sm btn-outline-secondary toggle-stats" data-toggle="collapse" data-target="#statsSection">
                                        <i class="fa fa-chevron-down"></i>
                                    </button>
                                </div>
                                <div class="card-body collapse show" id="statsSection">
                                    <style>
                                        .chart-container {
                                            position: relative;
                                            height: 170px !important;
                                            width: 100% !important;
                                            margin-bottom: 30px;
                                        }
                                        .chart-wrapper {
                                            background: white;
                                            border-radius: 10px;
                                            padding: 20px;
                                            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                                            margin-bottom: 30px;
                                            height: 170px !important;
                                            width: 100% !important;
                                        }
                                        .section-title {
                                            font-size: 16px;
                                            font-weight: 600;
                                            color: #495057;
                                            margin-bottom: 15px;
                                            text-transform: uppercase;
                                            letter-spacing: 1px;
                                        }
                                        .toggle-stats {
                                            padding: 0.25rem 0.5rem;
                                            transition: transform 0.3s ease;
                                        }
                                        .toggle-stats.collapsed i {
                                            transform: rotate(-90deg);
                                        }
                                        .chart-wrapper {
                                            background: white;
                                            border-radius: 10px;
                                            padding: 20px;
                                            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                                            margin-bottom: 30px;
                                            height: 300px !important; /* Make this consistent */
                                            width: 100% !important;
                                        }
                                    </style>

                                    <!-- Status Section -->
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="status-section">
                                                <div class="section-title">Status Distribution</div>
                                                <div class="chart-wrapper" >
                                                    <canvas id="statusChart"></canvas>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <!-- Priority Section -->
                                            <div class="priority-section">
                                                <div class="section-title">Priority Distribution</div>
                                                <div class="chart-wrapper" >
                                                    <canvas id="priorityChart"></canvas>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Project Details Section -->
                    <div class="mb-5">
                        @include('projects.edit')
                    </div>

                    <!-- Issues Cards Section -->
                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4>Issues</h4>
                        </div>

                        <!-- Fixed Add New Issue Button -->
                        <div class="add-issue-fab">
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addIssueModal">
                                <i class="fa fa-plus"></i>
                                Add New Issue
                            </button>
                        </div>

                        <!-- Add Issue Modal -->
                        <div class="modal fade" id="addIssueModal" tabindex="-1" role="dialog" aria-labelledby="addIssueModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="addIssueModalLabel">Add New Issue</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="addIssueForm">
                                            @csrf
                                            <input type="hidden" name="project_id" value="{{ $project->id }}">

                                            <div class="form-group">
                                                <label for="title">Title</label>
                                                <input type="text" class="form-control" id="title" name="title" required>
                                            </div>

                                            <div class="form-group">
                                                <label for="description">Description</label>
                                                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="status">Status</label>
                                                        <select class="form-control" id="status" name="status" required>
                                                            <option value="open">Open</option>
                                                            <option value="in_progress">In Progress</option>
                                                            <option value="resolved">Resolved</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="priority">Priority</label>
                                                        <select class="form-control" id="priority" name="priority" required>
                                                            <option value="low">Low</option>
                                                            <option value="medium">Medium</option>
                                                            <option value="high">High</option>
                                                            <option value="critical">Critical</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="assignees">Assign To</label>
                                                <select class="form-control" id="assignees" name="assignees[]" multiple>
                                                    @foreach($users as $user)
                                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <!-- Resolution Dates -->
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="target_resolution_date">Target Resolution Date</label>
                                                        <input type="date" class="form-control" id="target_resolution_date" name="target_resolution_date">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="actual_resolution_date">Actual Resolution Date</label>
                                                        <input type="date" class="form-control" id="actual_resolution_date" name="actual_resolution_date">
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-primary" id="saveIssueBtn">
                                            <span class="button-text">Save Issue</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Filters -->
                        <div class="card mb-3">
                            <div class="card-body">
                                <form action="{{ route('projects.show', $project->id) }}" method="GET" class="row">
                                    <div class="col-md-3 mb-2">
                                        <label for="status_filter">Status</label>
                                        <select class="form-control form-control-sm" id="status_filter" name="status">
                                            <option value="">All Statuses</option>
                                            <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                                            <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                            <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                        </select>
                                    </div>

                                    <div class="col-md-3 mb-2">
                                        <label for="priority_filter">Priority</label>
                                        <select class="form-control form-control-sm" id="priority_filter" name="priority">
                                            <option value="">All Priorities</option>
                                            <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                                            <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                                            <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                                            <option value="critical" {{ request('priority') == 'critical' ? 'selected' : '' }}>Critical</option>
                                        </select>
                                    </div>

                                    <div class="col-md-3 mb-2">
                                        <label for="assigned_filter">Assigned To</label>
                                        <select class="form-control form-control-sm" id="assigned_filter" name="assigned_to">
                                            <option value="">All Users</option>
                                            <option value="unassigned" {{ request('assigned_to') == 'unassigned' ? 'selected' : '' }}>Unassigned</option>
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}" {{ request('assigned_to') == $user->id ? 'selected' : '' }}>
                                                    {{ $user->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-3 mb-2 d-flex align-items-end">
                                        <button type="submit" class="btn btn-info btn-sm mr-1">Filter</button>
                                        <a href="{{ route('projects.show', $project->id) }}" class="btn btn-light btn-sm">Reset</a>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Issues Cards -->
                        <div class="row">
                            @forelse($issues as $issue)
                                <div class="col-12 mb-3">
                                    <div class="issue-card" data-issue-id="{{ $issue->id }}">
                                        <div class="card minicard h-100 shadow-sm">
                                            <div class="card-body p-3">
                                                <style>
                                                    .issue-card {
                                                        transition: all 0.3s ease;
                                                    }
                                                    .issue-card:hover {
                                                        transform: translateY(-2px);
                                                        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
                                                    }
                                                    .issue-card .form-control {
                                                        border: 1px solid #e9ecef;
                                                        transition: border-color 0.2s ease;
                                                    }
                                                    .issue-card .form-control:focus {
                                                        border-color: #80bdff;
                                                        box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
                                                    }
                                                    .issue-card .Priority-pdg {
                                                        top: 0;
                                                        right: 0;
                                                    }
                                                    .issue-card .Status-pdg {
                                                        top: 0;
                                                        left: 0;
                                                    }
                                                    .avatar-xxs {
                                                        width: 20px !important;
                                                        height: 20px !important;
                                                        font-size: 10px;
                                                        display: inline-flex;
                                                        align-items: center;
                                                        justify-content: center;
                                                    }
                                                    .comments-preview .avatar-group {
                                                        display: flex;
                                                        flex-wrap: wrap;
                                                    }
                                                    .comments-preview .avatar-group .avatar {
                                                        margin-right: -6px;
                                                        border: 1px solid #fff;
                                                    }
                                                    .card-actions {
                                                        display: inline-block;
                                                    }
                                                    .save-button {
                                                        position: absolute;
                                                        bottom: 10px;
                                                        right: 10px;
                                                        opacity: 0;
                                                        transition: opacity 0.3s ease;
                                                    }
                                                    .card.changed .save-button {
                                                        opacity: 1;
                                                    }
                                                    /* Add New Issue Button Styles */
                                                    .add-issue-fab {
                                                        position: fixed;
                                                        right: 30px;
                                                        bottom: 30px;
                                                        z-index: 1000;
                                                        transition: all 0.3s ease;
                                                    }
                                                    .add-issue-fab:hover {
                                                        transform: scale(1.1);
                                                        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
                                                    }
                                                    .add-issue-fab .btn {
                                                        border-radius: 50px;
                                                        padding: 12px 25px;
                                                        font-weight: 600;
                                                        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                                                        display: flex;
                                                        align-items: center;
                                                        gap: 8px;
                                                    }
                                                    .add-issue-fab .btn i {
                                                        font-size: 16px;
                                                    }
                                                </style>

                                                <!-- Card Actions -->


                                                <div class="row">
                                                    <!-- Left Column - Main Content -->
                                                    <div class="col-md-8">
                                                        <!-- Tags/Categories -->
                                                        <div class="mb-2 d-inline-block">
                                                            <span class="badge badge-light mr-2">#{{ $issue->id }}</span>
                                                            @if($issue->labels)
                                                                @foreach($issue->labels as $label)
                                                                    <span class="badge badge-info mr-2">{{ $label }}</span>
                                                                @endforeach
                                                            @endif
                                                        </div>
                                                        <div class="card-actions">
                                                            <button type="button" class="btn btn-sm btn-outline-primary mr-1" onclick="showComments({{ $issue->id }})">
                                                                <i class="fa fa-comments"></i>
                                                            </button>
                                                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="showHistory({{ $issue->id }})">
                                                                <i class="fa fa-history"></i>
                                                            </button>
                                                        </div>
                                                        <!-- Title -->
                                                        <div class="form-group mb-2">
                                                            <input type="text"
                                                                   class="form-control form-control-sm"
                                                                   value="{{ $issue->title }}"
                                                                   data-field="title"
                                                                   data-issue-id="{{ $issue->id }}"
                                                                   placeholder="Issue Title">
                                                        </div>

                                                        <!-- Description -->
                                                        <div class="form-group mb-2">
                                                            <textarea class="form-control"
                                                                      rows="2"
                                                                      data-field="description"
                                                                      data-issue-id="{{ $issue->id }}"
                                                                      placeholder="Issue Description">{{ $issue->description }}</textarea>
                                                        </div>
                                                        <!-- Resolution Dates -->
                                                        <div class="row">
                                                            <div class="col-6">
                                                                <div class="form-group mb-0">
                                                                    <label class="small text-muted mb-0">Target</label>
                                                                    <input type="date"
                                                                           class="form-control form-control-sm"
                                                                           value="{{ $issue->target_resolution_date ? $issue->target_resolution_date->format('Y-m-d') : '' }}"
                                                                           data-field="target_resolution_date"
                                                                           data-issue-id="{{ $issue->id }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-6">
                                                                <div class="form-group mb-0">
                                                                    <label class="small text-muted mb-0">Actual</label>
                                                                    <input type="date"
                                                                           class="form-control form-control-sm"
                                                                           value="{{ $issue->actual_resolution_date ? $issue->actual_resolution_date->format('Y-m-d') : '' }}"
                                                                           data-field="actual_resolution_date"
                                                                           data-issue-id="{{ $issue->id }}">
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>

                                                    <!-- Right Column - Metadata -->
                                                    <div class="col-md-4">
                                                        <div class="d-flex flex-column h-100">
                                                            <!-- Priority and Status -->
                                                            <div class="row mb-2">
                                                                <div class="Priority-pdg col-6">
                                                                    <select class="form-control form-control-sm" data-field="priority" data-issue-id="{{ $issue->id }}">
                                                                        <option value="low" {{ $issue->priority == 'low' ? 'selected' : '' }}>Low</option>
                                                                        <option value="medium" {{ $issue->priority == 'medium' ? 'selected' : '' }}>Medium</option>
                                                                        <option value="high" {{ $issue->priority == 'high' ? 'selected' : '' }}>High</option>
                                                                        <option value="critical" {{ $issue->priority == 'critical' ? 'selected' : '' }}>Critical</option>
                                                                    </select>
                                                                </div>
                                                                <div class="Status-pdg col-6">
                                                                    <select class="form-control form-control-sm" data-field="status" data-issue-id="{{ $issue->id }}">
                                                                        <option value="open" {{ $issue->status == 'open' ? 'selected' : '' }}>Open</option>
                                                                        <option value="in_progress" {{ $issue->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                                                        <option value="resolved" {{ $issue->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <!-- Assignees -->
                                                            <div class="mb-2">
                                                                <small class="text-muted d-block mb-1">Assigned to:</small>
                                                                <select class="form-control form-control-sm" data-field="assignees" data-issue-id="{{ $issue->id }}" multiple>
                                                                    @foreach($users as $user)
                                                                        <option value="{{ $user->id }}" {{ $issue->assignees->contains($user->id) ? 'selected' : '' }}>
                                                                            {{ $user->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>


                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Save Button -->
                                                <button type="button"
                                                        class="btn btn-sm btn-primary save-button"
                                                        data-issue-id="{{ $issue->id }}"
                                                        disabled>
                                                    Save Changes
                                                </button>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12">
                                    <div class="alert alert-light text-center">
                                        No issues found for this project.
                                    </div>
                                </div>
                            @endforelse
                        </div>

                        <!-- Comments Modal -->
                        <div class="modal fade" id="commentsModal" tabindex="-1" role="dialog">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Comments</h5>
                                        <button type="button" class="close" data-dismiss="modal">
                                            <span>&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body" id="commentsContent">
                                        <!-- Comments will be loaded here -->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- History Modal -->
                        <div class="modal fade" id="historyModal" tabindex="-1" role="dialog">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">History</h5>
                                        <button type="button" class="close" data-dismiss="modal">
                                            <span>&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body" id="historyContent">
                                        <!-- History will be loaded here -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('components.issue-detail-modal')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{ asset('js/app.js') }}"></script>
<script>
    console.log('About to initialize charts');
    // Debug data from controller
    console.log('Project data:', {
        issues: @json($issues),
        statusCounts: {
            open: {{ $issues->where('status', 'open')->count() }},
            inProgress: {{ $issues->where('status', 'in_progress')->count() }},
            resolved: {{ $issues->where('status', 'resolved')->count() }},
            closed: {{ $issues->where('status', 'closed')->count() }}
        },
        priorityCounts: {
            low: {{ $issues->where('priority', 'low')->count() }},
            medium: {{ $issues->where('priority', 'medium')->count() }},
            high: {{ $issues->where('priority', 'high')->count() }},
            critical: {{ $issues->where('priority', 'critical')->count() }}
        }
    });

    // Initialize charts when DOM is ready
    $(document).ready(function() {
        // Set up CSRF token for all AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Add event listeners for input changes
        $('.issue-card').each(function() {
            const issueCard = $(this);
            const saveButton = issueCard.find('.save-button');
            const originalValues = {};

            // Store original values
            issueCard.find('input, select, textarea').each(function() {
                const field = $(this);
                originalValues[field.attr('name') || field.attr('data-field')] = field.val();
            });

            // Add change event listener to all inputs
            issueCard.find('input, select, textarea').on('change', function() {
                const currentValues = {};
                issueCard.find('input, select, textarea').each(function() {
                    const field = $(this);
                    currentValues[field.attr('name') || field.attr('data-field')] = field.val();
                });

                // Check if any value has changed
                const hasChanges = Object.keys(originalValues).some(key =>
                    originalValues[key] !== currentValues[key]
                );

                // Enable/disable save button based on changes
                saveButton.prop('disabled', !hasChanges);

                // Add/remove changed class to card
                if (hasChanges) {
                    issueCard.find('.card').addClass('changed');
                } else {
                    issueCard.find('.card').removeClass('changed');
                }
            });

            // Add save button click handler
            saveButton.on('click', function() {
                const issueId = issueCard.data('issue-id');
                const formData = new FormData();

                // Collect all values, not just changed ones
                issueCard.find('input, select, textarea').each(function() {
                    const field = $(this);
                    const fieldName = field.attr('name') || field.attr('data-field');
                    const value = field.is('select') ? field.val() : field.val();
                    formData.append(fieldName, value);
                });

                // Add CSRF token
                formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

                // Send AJAX request to update the issue
                $.ajax({
                    url: `/issues/${issueId}/ajax-update`,
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            // Update original values to match new values
                            issueCard.find('input, select, textarea').each(function() {
                                const field = $(this);
                                const fieldName = field.attr('name') || field.attr('data-field');
                                originalValues[fieldName] = field.val();
                            });

                            // Disable save button and remove changed class
                            saveButton.prop('disabled', true);
                            issueCard.find('.card').removeClass('changed');

                            // Show success message
                            if (typeof toastr !== 'undefined') {
                                toastr.success('Changes saved successfully!');
                            } else {
                                alert('Changes saved successfully!');
                            }
                        } else {
                            throw new Error(response.message || 'Failed to save changes');
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'Error saving changes';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        if (typeof toastr !== 'undefined') {
                            toastr.error(errorMessage);
                        } else {
                            alert(errorMessage);
                        }
                    }
                });
            });
        });

        console.log('jQuery ready event fired');

        // Get all unique dates from issues and group them by week
        const allDates = @json($issues->pluck('created_at')->map(function($date) {
            return \Carbon\Carbon::parse($date)->startOfWeek()->format('Y-m-d');
        })->unique()->sort()->values());

        // Prepare data for each status over time, grouped by week
        const statusData = {
            open: @json($issues->where('status', 'open')->pluck('created_at')->map(function($date) {
                return \Carbon\Carbon::parse($date)->startOfWeek()->format('Y-m-d');
            })->countBy()->toArray()),
            inProgress: @json($issues->where('status', 'in_progress')->pluck('created_at')->map(function($date) {
                return \Carbon\Carbon::parse($date)->startOfWeek()->format('Y-m-d');
            })->countBy()->toArray()),
            resolved: @json($issues->where('status', 'resolved')->pluck('created_at')->map(function($date) {
                return \Carbon\Carbon::parse($date)->startOfWeek()->format('Y-m-d');
            })->countBy()->toArray()),
            closed: @json($issues->where('status', 'closed')->pluck('created_at')->map(function($date) {
                return \Carbon\Carbon::parse($date)->startOfWeek()->format('Y-m-d');
            })->countBy()->toArray())
        };

        // Prepare the data arrays for the chart
        const prepareData = (data, dates) => {
            return dates.map(date => data[date] || 0);
        };

        // Status Chart (Stacked Bar Chart)
        const statusChart = new Chart(
            document.getElementById('statusChart'),
            {
                type: 'bar',
                data: {
                    labels: allDates.map(date => {
                        const weekStart = new Date(date);
                        const weekEnd = new Date(date);
                        weekEnd.setDate(weekEnd.getDate() + 6);
                        return `${weekStart.toLocaleDateString()} - ${weekEnd.toLocaleDateString()}`;
                    }),
                    datasets: [
                        {
                            label: 'Open',
                            data: prepareData(statusData.open, allDates),
                            backgroundColor: '#FF6384',
                            stack: 'Stack 0'
                        },
                        {
                            label: 'In Progress',
                            data: prepareData(statusData.inProgress, allDates),
                            backgroundColor: '#36A2EB',
                            stack: 'Stack 0'
                        },
                        {
                            label: 'Resolved',
                            data: prepareData(statusData.resolved, allDates),
                            backgroundColor: '#FFCE56',
                            stack: 'Stack 0'
                        },
                        {
                            label: 'Closed',
                            data: prepareData(statusData.closed, allDates),
                            backgroundColor: '#4BC0C0',
                            stack: 'Stack 0'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Issue Status Over Time (Weekly)'
                        },
                        legend: {
                            position: 'top'
                        }
                    },
                    scales: {
                        x: {
                            stacked: true,
                            title: {
                                display: true,
                                text: 'Week'
                            }
                        },
                        y: {
                            stacked: true,
                            title: {
                                display: true,
                                text: 'Number of Issues'
                            },
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            }
        );

        // Priority Chart (Bar Chart)
        const priorityChart = new Chart(
            document.getElementById('priorityChart'),
            {
                type: 'bar',
                data: {
                    labels: ['Low', 'Medium', 'High', 'Critical'],
                    datasets: [{
                        label: 'Number of Issues',
                        data: [
                            {{ $issues->where('priority', 'low')->count() }},
                            {{ $issues->where('priority', 'medium')->count() }},
                            {{ $issues->where('priority', 'high')->count() }},
                            {{ $issues->where('priority', 'critical')->count() }}
                        ],
                        backgroundColor: [
                            '#4BC0C0',
                            '#36A2EB',
                            '#FFCE56',
                            '#FF6384'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            }
        );
        console.log('About to initialize charts');
        console.log('Status Chart element exists:', !!document.getElementById('statusChart'));
        console.log('Priority Chart element exists:', !!document.getElementById('priorityChart'));
    });

    $(document).ready(function() {
        $('#saveIssueBtn').on('click', function() {
            const $form = $('#addIssueForm');
            const $button = $(this);
            const $buttonText = $button.find('.button-text');
            const $modal = $('#addIssueModal');

            // Show loading state
            $button.prop('disabled', true);
            $buttonText.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...');

            // Collect form data
            const formData = new FormData($form[0]);

            // Send AJAX request
            $.ajax({
                url: '{{ route("issues.store") }}',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    // Reset form and button state
                    $form[0].reset();
                    $button.prop('disabled', false);
                    $buttonText.text('Save Issue');

                    // Close modal - use only one method
                    $modal.modal('hide');

                    // Give a small delay before showing success message
                    setTimeout(function() {
                        // Show success message
                        if (typeof toastr !== 'undefined') {
                            toastr.success('Issue created successfully');
                        } else {
                            alert('Issue created successfully');
                        }

                        // Refresh issues list
                        $.ajax({
                            url: '{{ route("projects.show", $project->id) }}',
                            method: 'GET',
                            success: function(response) {
                                // Create a temporary div to parse the response
                                const $temp = $('<div>').html(response);

                                // Find the issues section in the response
                                const $newContent = $temp.find('.row:contains(".issue-card")');

                                // Preserve the card background color and styling
                                $newContent.find('.card.minicard').each(function() {
                                    $(this).css({
                                        'background-color': '#fff',
                                        'transition': 'all 0.3s ease'
                                    });
                                });

                                // Get the original container
                                const $originalContainer = $('.row:contains(".issue-card")');

                                // Replace the content while preserving the container
                                $originalContainer.html($newContent.html());

                                // Reinitialize all event handlers
                                initIssueCardEventHandlers();
                            },
                            error: function() {
                                if (typeof toastr !== 'undefined') {
                                    toastr.error('Failed to refresh issues list');
                                }
                            }
                        });
                    }, 300);
                },
                error: function(xhr) {
                    // Reset button state
                    $button.prop('disabled', false);
                    $buttonText.text('Save Issue');

                    // Show error message
                    if (typeof toastr !== 'undefined') {
                        toastr.error(xhr.responseJSON?.message || 'Error creating issue');
                    } else {
                        alert(xhr.responseJSON?.message || 'Error creating issue');
                    }

                    // Handle validation errors
                    if (xhr.status === 422 && xhr.responseJSON?.errors) {
                        const errors = xhr.responseJSON.errors;
                        Object.keys(errors).forEach(field => {
                            const $input = $(`[name="${field}"]`);
                            $input.addClass('is-invalid');

                            // Create feedback div if it doesn't exist
                            let $feedback = $input.next('.invalid-feedback');
                            if (!$feedback.length) {
                                $input.after('<div class="invalid-feedback"></div>');
                                $feedback = $input.next('.invalid-feedback');
                            }

                            $feedback.text(errors[field][0]);
                        });
                    }
                }
            });
        });

        // Function to reinitialize event handlers after refreshing issues list
        function initIssueCardEventHandlers() {
            // Reinitialize input change handlers for each issue card
            $('.issue-card').each(function() {
                const issueCard = $(this);
                const saveButton = issueCard.find('.save-button');
                const originalValues = {};

                // Store original values
                issueCard.find('input, select, textarea').each(function() {
                    const field = $(this);
                    originalValues[field.attr('name') || field.attr('data-field')] = field.val();
                });

                // Add change event listener to all inputs
                issueCard.find('input, select, textarea').on('change', function() {
                    const currentValues = {};
                    issueCard.find('input, select, textarea').each(function() {
                        const field = $(this);
                        currentValues[field.attr('name') || field.attr('data-field')] = field.val();
                    });

                    // Check if any value has changed
                    const hasChanges = Object.keys(originalValues).some(key =>
                        originalValues[key] !== currentValues[key]
                    );

                    // Enable/disable save button based on changes
                    saveButton.prop('disabled', !hasChanges);

                    // Add/remove changed class to card
                    if (hasChanges) {
                        issueCard.find('.card').addClass('changed');
                    } else {
                        issueCard.find('.card').removeClass('changed');
                    }
                });

                // Add save button click handler
                saveButton.on('click', function() {
                    const issueId = issueCard.data('issue-id');
                    const formData = new FormData();

                    // Collect all values
                    issueCard.find('input, select, textarea').each(function() {
                        const field = $(this);
                        const fieldName = field.attr('name') || field.attr('data-field');
                        const value = field.is('select') ? field.val() : field.val();
                        formData.append(fieldName, value);
                    });

                    // Add CSRF token
                    formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

                    // Send AJAX request to update the issue
                    $.ajax({
                        url: `/issues/${issueId}/ajax-update`,
                        method: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            if (response.success) {
                                // Update original values to match new values
                                issueCard.find('input, select, textarea').each(function() {
                                    const field = $(this);
                                    const fieldName = field.attr('name') || field.attr('data-field');
                                    originalValues[fieldName] = field.val();
                                });

                                // Disable save button and remove changed class
                                saveButton.prop('disabled', true);
                                issueCard.find('.card').removeClass('changed');

                                // Show success message
                                if (typeof toastr !== 'undefined') {
                                    toastr.success('Changes saved successfully!');
                                } else {
                                    alert('Changes saved successfully!');
                                }
                            } else {
                                throw new Error(response.message || 'Failed to save changes');
                            }
                        },
                        error: function(xhr) {
                            let errorMessage = 'Error saving changes';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            }
                            if (typeof toastr !== 'undefined') {
                                toastr.error(errorMessage);
                            } else {
                                alert(errorMessage);
                            }
                        }
                    });
                });

                // Add comment and history button handlers
                issueCard.find('.btn-outline-primary').on('click', function() {
                    showComments(issueCard.data('issue-id'));
                });

                issueCard.find('.btn-outline-secondary').on('click', function() {
                    showHistory(issueCard.data('issue-id'));
                });
            });
        }

        // Remove validation classes when input changes
        $('#addIssueForm input, #addIssueForm select, #addIssueForm textarea').on('input change', function() {
            $(this).removeClass('is-invalid');
            $(this).next('.invalid-feedback').text('');
        });
    });

    // Add these functions before the initIssueCardEventHandlers function
    function showComments(issueId) {
        $.ajax({
            url: `/issues/${issueId}/comments`,
            method: 'GET',
            success: function(response) {
                $('#commentsContent').html(response);
                $('#commentsModal').modal('show');
            },
            error: function() {
                if (typeof toastr !== 'undefined') {
                    toastr.error('Failed to load comments');
                } else {
                    alert('Failed to load comments');
                }
            }
        });
    }

    function showHistory(issueId) {
        $.ajax({
            url: `/issues/${issueId}/history`,
            method: 'GET',
            success: function(response) {
                $('#historyContent').html(response);
                $('#historyModal').modal('show');
            },
            error: function() {
                if (typeof toastr !== 'undefined') {
                    toastr.error('Failed to load history');
                } else {
                    alert('Failed to load history');
                }
            }
        });
    }
</script>

@endsection
