@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card mainCard" style="background-color: #45bddb;">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ $project->name }} ({{ $project->code }})</span>
                    <div>
                        {{--  <a href="{{ route('projects.edit', $project) }}" class="btn btn-primary btn-sm">Edit Project</a>  --}}
                        <button id="exportPdfBtn" class="btn btn-info btn-sm" style="
                        background-color: #b6150c;
                        border-radius: 5px;
                        padding: 5px 10px;
                    ">
                            <i class="fa fa-file-pdf-o"></i> Export PDF
                        </button>
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

                    <!-- Issues Section -->
                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4>Issues</h4>
                        </div>

                        <!-- Fixed Add New Issue Button -->
                        @if(Auth::user()->role === 'o-admin' || Auth::user()->role === 'cm' || Auth::user()->role === 'pm' || $project->members->contains(Auth::user()))
                        <div class="add-issue-fab">
                            <button type="button" class="btn btn-primary" id="addNewIssueRow">
                                <i class="fa fa-plus"></i>
                                Add New Issue
                            </button>
                        </div>
                        @endif

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
                                                <textarea class="form-control" id="title" name="title" rows="4" required></textarea>
                                            </div>

                                            <div class="form-group">
                                                <label for="description">Description</label>
                                                <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
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
                                                        @if(in_array($user->role, ['gm', 'cm', 'dm']))
                                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                        @endif
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
                                                @if(in_array($user->role, ['gm', 'cm', 'dm']))
                                                    <option value="{{ $user->id }}" {{ request('assigned_to') == $user->id ? 'selected' : '' }}>
                                                        {{ $user->name }}
                                                    </option>
                                                @endif
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

                        <!-- Table Styles -->
                        <style>
                          .table-responsive {
                            overflow-x: auto;
                          }

                          .issues-table {
                            width: 100%;
                            border-collapse: collapse;
                            background-color: #fff;
                            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                            border-radius: 8px;
                          }

                          .issues-table th {
                            background-color: #f8f9fa;
                            padding: 12px;
                            text-align: left;
                            font-weight: 600;
                            border-bottom: 2px solid #dee2e6;
                          }

                          .issues-table td {
                            padding: 12px;
                            border-bottom: 1px solid #dee2e6;
                            vertical-align: middle;
                          }

                          .issues-table tr:hover {
                            background-color: #f8f9fa;
                          }

                          .issues-table .form-control {
                            border: 1px solid #e9ecef;
                            transition: border-color 0.2s ease;
                          }

                          .issues-table .form-control:focus {
                            border-color: #80bdff;
                            box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
                          }

                          .status-open {
                            background-color: #ffeeba;
                            color: #856404;
                            padding: 4px 8px;
                            border-radius: 4px;
                            font-size: 12px;
                          }

                          .status-in_progress {
                            background-color: #b8daff;
                            color: #004085;
                            padding: 4px 8px;
                            border-radius: 4px;
                            font-size: 12px;
                          }

                          .status-resolved {
                            background-color: #c3e6cb;
                            color: #155724;
                            padding: 4px 8px;
                            border-radius: 4px;
                            font-size: 12px;
                          }

                          .priority-low {
                            background-color: #d4edda;
                            color: #155724;
                            padding: 4px 8px;
                            border-radius: 4px;
                            font-size: 12px;
                          }

                          .priority-medium {
                            background-color: #fff3cd;
                            color: #856404;
                            padding: 4px 8px;
                            border-radius: 4px;
                            font-size: 12px;
                          }

                          .priority-high {
                            background-color: #f8d7da;
                            color: #721c24;
                            padding: 4px 8px;
                            border-radius: 4px;
                            font-size: 12px;
                          }

                          .priority-critical {
                            background-color: #dc3545;
                            color: #fff;
                            padding: 4px 8px;
                            border-radius: 4px;
                            font-size: 12px;
                          }

                          .save-row-button {
                            opacity: 0;
                            transition: opacity 0.3s ease;
                          }

                          .row-changed .save-row-button {
                            opacity: 1;
                          }

                          .badge-issue-id {
                            background-color: #6c757d;
                            color: white;
                            font-size: 12px;
                            border-radius: 4px;
                            padding: 3px 6px;
                          }

                          .description-cell {
                            max-width: 300px;
                          }

                          .description-cell textarea {
                            min-height: 80px;
                          }

                          /* Style for title textarea */
                          textarea[data-field="title"] {
                            min-height: 60px;
                            resize: vertical;
                          }

                          /* Style for description textarea */
                          textarea[data-field="description"] {
                            min-height: 80px;
                            resize: vertical;
                          }

                          .action-buttons {
                            display: flex;
                            gap: 5px;
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
                              border-radius: 50px;
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

                        <!-- Issues Table -->
                        <div class="table-responsive">
                          <table class="issues-table">
                            <thead>
                              <tr>
                                <th width="20%">Title</th>
                                <th width="25%">Description</th>
                                <th width="8%">Status</th>
                                <th width="8%">Priority</th>
                                <th width="15%">Assignees</th>
                                <th width="9%">Target Date</th>
                                <th width="9%">Actual Date</th>
                                <th width="6%">Actions</th>
                              </tr>
                            </thead>
                            <tbody>
                              @forelse($issues as $issue)
                              <tr class="issue-row" data-issue-id="{{ $issue->id }}">
                                <td>
                                  <textarea
                                         class="form-control form-control-sm"
                                         data-field="title"
                                         data-issue-id="{{ $issue->id }}"
                                         placeholder="Issue Title"
                                         rows="4">{{ $issue->title }}</textarea>
                                  @if($issue->labels)
                                    @foreach($issue->labels as $label)
                                      <span class="badge badge-info">{{ $label }}</span>
                                    @endforeach
                                  @endif
                                </td>
                                <td class="description-cell">
                                  <textarea class="form-control form-control-sm"
                                            rows="4"
                                            data-field="description"
                                            data-issue-id="{{ $issue->id }}"
                                            placeholder="Issue Description">{{ $issue->description }}</textarea>
                                </td>
                                <td>
                                  <select class="form-control form-control-sm" data-field="status" data-issue-id="{{ $issue->id }}">
                                    <option value="open" {{ $issue->status == 'open' ? 'selected' : '' }}>Open</option>
                                    <option value="in_progress" {{ $issue->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="resolved" {{ $issue->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                  </select>
                                </td>
                                <td>
                                  <select class="form-control form-control-sm" data-field="priority" data-issue-id="{{ $issue->id }}">
                                    <option value="low" {{ $issue->priority == 'low' ? 'selected' : '' }}>Low</option>
                                    <option value="medium" {{ $issue->priority == 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="high" {{ $issue->priority == 'high' ? 'selected' : '' }}>High</option>
                                    <option value="critical" {{ $issue->priority == 'critical' ? 'selected' : '' }}>Critical</option>
                                  </select>
                                </td>
                                <td>
                                  <select class="form-control form-control-sm" data-field="assignees" data-issue-id="{{ $issue->id }}" multiple>
                                    @foreach($users as $user)
                                      @if(in_array($user->role, ['gm', 'cm', 'dm']) || $user->id == $project->manager_id)
                                        <option value="{{ $user->id }}" {{ $issue->assignees->contains($user->id) ? 'selected' : '' }}>
                                          {{ $user->name }}{{ $user->id == $project->manager_id ? ' (Manager)' : '' }}
                                        </option>
                                      @endif
                                    @endforeach
                                  </select>
                                </td>
                                <td>
                                  <input type="date"
                                         class="form-control form-control-sm"
                                         value="{{ $issue->target_resolution_date ? $issue->target_resolution_date->format('Y-m-d') : '' }}"
                                         data-field="target_resolution_date"
                                         data-issue-id="{{ $issue->id }}">
                                </td>
                                <td>
                                  <input type="date"
                                         class="form-control form-control-sm"
                                         value="{{ $issue->actual_resolution_date ? $issue->actual_resolution_date->format('Y-m-d') : '' }}"
                                         data-field="actual_resolution_date"
                                         data-issue-id="{{ $issue->id }}">
                                </td>
                                <td>
                                  <div class="action-buttons">
                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="showComments({{ $issue->id }})">
                                      <i class="fa fa-comments"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="showHistory({{ $issue->id }})">
                                      <i class="fa fa-history"></i>
                                    </button>
                                    <button type="button"
                                            class="btn btn-sm btn-primary save-row-button"
                                            data-issue-id="{{ $issue->id }}"
                                            disabled>
                                      <i class="fa fa-save"></i>
                                    </button>
                                    <button type="button"
                                            class="btn btn-sm btn-danger delete-issue"
                                            data-issue-id="{{ $issue->id }}">
                                      <i class="fa fa-trash"></i>
                                    </button>
                                  </div>
                                </td>
                              </tr>
                              @empty
                              <tr>
                                <td colspan="9" class="text-center">
                                  <div class="alert alert-light">No issues found for this project.</div>
                                </td>
                              </tr>
                              @endforelse
                            </tbody>
                          </table>
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
                                    <div class="modal-body">
                                        <div id="commentsList">
                                            <!-- Comments will be loaded here -->
                                        </div>
                                        @if(Auth::user()->role === 'o-admin' || Auth::user()->role === 'cm' || Auth::user()->role === 'pm' || $project->members->contains(Auth::user()))
                                        <div class="mt-4">
                                            <form id="addCommentForm">
                                                @csrf
                                                <div class="form-group">
                                                    <label for="commentContent">Add Your Comment</label>
                                                    <textarea class="form-control" id="commentContent" name="description" rows="3" required placeholder="Type your comment here..."></textarea>
                                                    <div class="invalid-feedback"></div>
                                                </div>
                                                <button type="submit" class="btn btn-primary">
                                                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                                    Post Comment
                                                </button>
                                            </form>
                                        </div>
                                        @endif
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('js/app.js') }}"></script>
<script>
    // Add custom SweetAlert2 toast configuration
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        },
        width: '300px'
    });

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

        // Initialize issue row event handlers
        initIssueRowEventHandlers();

        // Handle Add New Issue button click
        $('#addNewIssueRow').on('click', function() {
            // Generate a temporary ID for the new row (negative to avoid conflicts with real IDs)
            const tempId = -Math.floor(Math.random() * 1000);

            // Create the new row HTML
            const newRowHtml = `
              <tr class="issue-row new-issue-row" data-issue-id="${tempId}">
                <td>
                  <textarea
                         class="form-control form-control-sm"
                         data-field="title"
                         data-issue-id="${tempId}"
                         placeholder="Issue Title"
                         rows="4"></textarea>
                  <span class="badge-issue-id">NEW</span>
                </td>
                <td class="description-cell">
                  <textarea class="form-control form-control-sm"
                            rows="4"
                            data-field="description"
                            data-issue-id="${tempId}"
                            placeholder="Issue Description"></textarea>
                </td>
                <td>
                  <select class="form-control form-control-sm" data-field="status" data-issue-id="${tempId}">
                    <option value="open" selected>Open</option>
                    <option value="in_progress">In Progress</option>
                    <option value="resolved">Resolved</option>
                  </select>
                </td>
                <td>
                  <select class="form-control form-control-sm" data-field="priority" data-issue-id="${tempId}">
                    <option value="low">Low</option>
                    <option value="medium" selected>Medium</option>
                    <option value="high">High</option>
                    <option value="critical">Critical</option>
                  </select>
                </td>
                <td>
                  <select class="form-control form-control-sm" data-field="assignees" data-issue-id="${tempId}" multiple>
                    @foreach($users as $user)
                      @if(in_array($user->role, ['gm', 'cm', 'dm']) || $user->id == $project->manager_id)
                        <option value="{{ $user->id }}">
                          {{ $user->name }}{{ $user->id == $project->manager_id ? ' (Manager)' : '' }}
                        </option>
                      @endif
                    @endforeach
                  </select>
                </td>
                <td>
                  <input type="date"
                         class="form-control form-control-sm"
                         value=""
                         data-field="target_resolution_date"
                         data-issue-id="${tempId}">
                </td>
                <td>
                  <input type="date"
                         class="form-control form-control-sm"
                         value=""
                         data-field="actual_resolution_date"
                         data-issue-id="${tempId}">
                </td>
                <td>
                  <div class="action-buttons">
                    <button type="button" class="btn btn-sm btn-primary save-new-issue-button" data-issue-id="${tempId}">
                      <i class="fa fa-save"></i> Save
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger cancel-new-issue-button" data-issue-id="${tempId}">
                      <i class="fa fa-times"></i>
                    </button>
                  </div>
                </td>
              </tr>
            `;

            // Add the new row at the top of the table
            $('.issues-table tbody').prepend(newRowHtml);

            // Initialize the new row's event handlers
            initNewIssueRowEventHandlers(tempId);

            // Focus on title input
            $('.issues-table tbody tr:first-child input[data-field="title"]').focus();
        });

        console.log('jQuery ready event fired');

        // Create a simple status distribution chart (doughnut chart)
        const statusChart = new Chart(
            document.getElementById('statusChart'),
            {
                type: 'doughnut',
                data: {
                    labels: ['Open', 'In Progress', 'Resolved', 'Closed'],
                    datasets: [{
                        label: 'Number of Issues',
                        data: [
                            {{ $issues->where('status', 'open')->count() }},
                            {{ $issues->where('status', 'in_progress')->count() }},
                            {{ $issues->where('status', 'resolved')->count() }},
                            {{ $issues->where('status', 'closed')->count() }}
                        ],
                        backgroundColor: [
                            '#FF6384', // Open - Red
                            '#36A2EB', // In Progress - Blue
                            '#FFCE56', // Resolved - Yellow
                            '#4BC0C0'  // Closed - Green
                        ],
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Current Status Distribution'
                        },
                        legend: {
                            position: 'right'
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

    function initIssueRowEventHandlers() {
        $('.issue-row').each(function() {
            const issueRow = $(this);
            const saveButton = issueRow.find('.save-row-button');
            const originalValues = {};

            // Store original values
            issueRow.find('input, select, textarea').each(function() {
                const field = $(this);
                originalValues[field.attr('name') || field.attr('data-field')] = field.val();
            });

            // Add change event listener to all inputs
            issueRow.find('input, select, textarea').on('change', function() {
                const currentValues = {};
                issueRow.find('input, select, textarea').each(function() {
                    const field = $(this);
                    currentValues[field.attr('name') || field.attr('data-field')] = field.val();
                });

                // Check if any value has changed
                const hasChanges = Object.keys(originalValues).some(key =>
                    originalValues[key] !== currentValues[key]
                );

                // Enable/disable save button based on changes
                saveButton.prop('disabled', !hasChanges);

                // Add/remove changed class to row
                if (hasChanges) {
                    issueRow.addClass('row-changed');
                } else {
                    issueRow.removeClass('row-changed');
                }
            });

            // Add save button click handler
            saveButton.on('click', function() {
                const issueId = issueRow.data('issue-id');
                const formData = new FormData();

                // Collect all values
                issueRow.find('input, select, textarea').each(function() {
                    const field = $(this);
                    const fieldName = field.attr('name') || field.attr('data-field');

                    // Handle multiple select field differently
                    if (field.is('select[multiple]')) {
                        // Get selected values as array
                        const values = field.val() || [];

                        // Append each value separately with the same name (creates array on server side)
                        if (values.length > 0) {
                            values.forEach(value => {
                                // Use assigned_to[] instead of assignees[] if this is the assignees field
                                const fieldNameToUse = fieldName === 'assignees' ? 'assigned_to[]' : `${fieldName}[]`;
                                formData.append(fieldNameToUse, value);
                            });
                        } else {
                            // If nothing selected, send empty array to clear assignees
                            const fieldNameToUse = fieldName === 'assignees' ? 'assigned_to[]' : `${fieldName}[]`;
                            formData.append(fieldNameToUse, '');
                        }
                    } else {
                        // Handle regular fields
                        formData.append(fieldName, field.val() || '');
                    }
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
                            issueRow.find('input, select, textarea').each(function() {
                                const field = $(this);
                                const fieldName = field.attr('name') || field.attr('data-field');
                                originalValues[fieldName] = field.val();
                            });

                            // Disable save button and remove changed class
                            saveButton.prop('disabled', true);
                            issueRow.removeClass('row-changed');

                            // Show success message
                            if (typeof toastr !== 'undefined') {
                                toastr.success('Changes saved successfully!');
                            } else {
                                Toast.fire({
                                    icon: 'success',
                                    title: 'Changes saved successfully!'
                                });
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
                            Toast.fire({
                                icon: 'error',
                                title: errorMessage
                            });
                        }
                    }
                });
            });
        });
    }

    function initNewIssueRowEventHandlers(tempId) {
        const issueRow = $(`tr[data-issue-id="${tempId}"]`);

        // Handle Cancel button click
        issueRow.find('.cancel-new-issue-button').on('click', function() {
            issueRow.remove();
        });

        // Handle Save button click
        issueRow.find('.save-new-issue-button').on('click', function() {
            const formData = new FormData();

            // Add project_id to the form data
            formData.append('project_id', '{{ $project->id }}');

            // Collect all field values
            issueRow.find('input, select, textarea').each(function() {
                const field = $(this);
                const fieldName = field.attr('name') || field.attr('data-field');

                // Handle multiple select fields
                if (field.is('select[multiple]')) {
                    const values = field.val() || [];
                    if (values.length > 0) {
                        values.forEach(value => {
                            // Use assigned_to[] instead of assignees[] if this is the assignees field
                            const fieldNameToUse = fieldName === 'assignees' ? 'assigned_to[]' : `${fieldName}[]`;
                            formData.append(fieldNameToUse, value);
                        });
                    } else {
                        // Send empty array to clear all assignees
                        const fieldNameToUse = fieldName === 'assignees' ? 'assigned_to[]' : `${fieldName}[]`;
                        formData.append(fieldNameToUse, '');
                    }
                } else {
                    // Regular fields
                    formData.append(fieldName, field.val() || '');
                }
            });

            // Add CSRF token
            formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

            // Show loading state
            const saveBtn = $(this);
            saveBtn.prop('disabled', true);
            saveBtn.html('<i class="fa fa-spinner fa-spin"></i>');

            // Send AJAX request to create the issue
            $.ajax({
                url: '{{ route("issues.store") }}',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    // Consider any successful HTTP response as success
                    // Show success message
                    if (typeof toastr !== 'undefined') {
                        toastr.success('Issue created successfully!');
                    } else {
                        Toast.fire({
                            icon: 'success',
                            title: 'Issue created successfully!'
                        });
                    }

                    // Remove the temporary row
                    issueRow.remove();

                    // Refresh the issues list
                    $.ajax({
                        url: '{{ route("projects.show", $project->id) }}',
                        method: 'GET',
                        success: function(response) {
                            // Create a temporary div to parse the response
                            const $temp = $('<div>').html(response);

                            // Find the issues table in the response
                            const $newContent = $temp.find('.issues-table tbody');

                            // Get the original container
                            const $originalContainer = $('.issues-table tbody');

                            // Replace the content while preserving the container
                            $originalContainer.html($newContent.html());

                            // Reinitialize all event handlers
                            initIssueRowEventHandlers();
                        },
                        error: function() {
                            if (typeof toastr !== 'undefined') {
                                toastr.error('Failed to refresh issues list');
                            } else {
                                Toast.fire({
                                    icon: 'error',
                                    title: 'Failed to refresh issues list'
                                });
                            }
                        }
                    });
                },
                error: function(xhr) {
                    // Reset button state
                    saveBtn.prop('disabled', false);
                    saveBtn.html('<i class="fa fa-save"></i> Save');

                    // Show error message
                    let errorMessage = 'Error creating issue';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }

                    if (typeof toastr !== 'undefined') {
                        toastr.error(errorMessage);
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: errorMessage
                        });
                    }

                    // Handle validation errors
                    if (xhr.status === 422 && xhr.responseJSON?.errors) {
                        const errors = xhr.responseJSON.errors;
                        Object.keys(errors).forEach(field => {
                            const $input = issueRow.find(`[data-field="${field}"]`);
                            $input.addClass('is-invalid');

                            // Add error message as tooltip
                            $input.attr('title', errors[field][0]);
                            $input.tooltip({
                                trigger: 'manual',
                                placement: 'top'
                            }).tooltip('show');

                            // Remove tooltip on input change
                            $input.one('input change', function() {
                                $(this).removeClass('is-invalid');
                                $(this).tooltip('dispose');
                            });
                        });
                    }
                }
            });
        });
    }

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
                            Toast.fire({
                                icon: 'success',
                                title: 'Issue created successfully'
                            });
                        }

                        // Refresh issues list
                        $.ajax({
                            url: '{{ route("projects.show", $project->id) }}',
                            method: 'GET',
                            success: function(response) {
                                // Create a temporary div to parse the response
                                const $temp = $('<div>').html(response);

                                // Find the issues table in the response
                                const $newContent = $temp.find('.issues-table tbody');

                                // Get the original container
                                const $originalContainer = $('.issues-table tbody');

                                // Replace the content while preserving the container
                                $originalContainer.html($newContent.html());

                                // Reinitialize all event handlers
                                initIssueRowEventHandlers();
                            },
                            error: function() {
                                if (typeof toastr !== 'undefined') {
                                    toastr.error('Failed to refresh issues list');
                                } else {
                                    Toast.fire({
                                        icon: 'error',
                                        title: 'Failed to refresh issues list'
                                    });
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
                        Toast.fire({
                            icon: 'error',
                            title: xhr.responseJSON?.message || 'Error creating issue'
                        });
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

        // Remove validation classes when input changes
        $('#addIssueForm input, #addIssueForm select, #addIssueForm textarea').on('input change', function() {
            $(this).removeClass('is-invalid');
            $(this).next('.invalid-feedback').text('');
        });
    });

    // Functions for showing comments and history
    function showComments(issueId) {
        // Clear any existing comments
        $('#commentsList').empty();

        // Show loading state
        $('#commentsList').html('<div class="text-center"><div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div></div>');

        $.ajax({
            url: `/projects/{{ $project->id }}/issues/${issueId}/comments`,
            method: 'GET',
            success: function(response) {
                $('#commentsList').html(response);
                $('#commentsModal').modal('show');

                // Initialize comment form handling
                initCommentForm(issueId);
            },
            error: function() {
                $('#commentsList').html('<div class="alert alert-danger">Failed to load comments. Please try again.</div>');
            }
        });
    }

    function initCommentForm(issueId) {
        const form = $('#addCommentForm');
        const textarea = form.find('textarea');
        const button = form.find('button[type="submit"]');
        const spinner = button.find('.spinner-border');
        const invalidFeedback = form.find('.invalid-feedback');

        // Clear any previous event handlers
        form.off('submit');

        form.on('submit', function(e) {
            e.preventDefault();

            const commentContent = textarea.val().trim();

            // Reset validation state
            textarea.removeClass('is-invalid');
            invalidFeedback.text('');

            if (!commentContent) {
                textarea.addClass('is-invalid');
                invalidFeedback.text('Please enter a comment');
                return;
            }

            // Disable form and show loading state
            button.prop('disabled', true);
            spinner.removeClass('d-none');
            textarea.prop('disabled', true);

            $.ajax({
                url: `/projects/{{ $project->id }}/issues/${issueId}/comments`,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    description: commentContent
                },
                success: function(response) {
                    // Clear the form
                    textarea.val('');

                    // Remove "No comments" message if it exists
                    if ($('.alert.alert-light').length) {
                        $('.alert.alert-light').remove();
                    }

                    // Add the new comment to the list
                    $('#commentsList').prepend(response);

                    // Show success message
                    if (typeof toastr !== 'undefined') {
                        toastr.success('Comment added successfully');
                    } else {
                        Toast.fire({
                            icon: 'success',
                            title: 'Comment added successfully'
                        });
                    }
                },
                error: function(xhr) {
                    let errorMessage = 'Failed to add comment';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }

                    if (typeof toastr !== 'undefined') {
                        toastr.error(errorMessage);
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: errorMessage
                        });
                    }
                },
                complete: function() {
                    // Re-enable form
                    button.prop('disabled', false);
                    spinner.addClass('d-none');
                    textarea.prop('disabled', false);
                    textarea.focus();
                }
            });
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
                    Toast.fire({
                        icon: 'error',
                        title: 'Failed to load history'
                    });
                }
            }
        });
    }

    // Export PDF functionality
    $('#exportPdfBtn').on('click', function() {
        const $button = $(this);
        const originalHtml = $button.html();

        // Show loading state
        $button.prop('disabled', true);
        $button.html('<i class="fa fa-spinner fa-spin"></i> Generating PDF...');

        // Make the request
        window.location.href = '{{ route("projects.export-pdf", $project) }}';

        // Reset button state after a delay
        setTimeout(() => {
            $button.prop('disabled', false);
            $button.html(originalHtml);
        }, 2000);
    });

    // Add delete issue functionality
    $(document).ready(function() {
        // Handle delete button clicks using event delegation
        $(document).on('click', '.delete-issue', function() {
            const issueId = $(this).data('issue-id');
            const $button = $(this);
            const $row = $button.closest('tr');

            if (confirm('Are you sure you want to delete this issue?')) {
                // Show loading state
                $button.prop('disabled', true);
                $button.html('<i class="fa fa-spinner fa-spin"></i>');

                // Send delete request using jQuery AJAX
                $.ajax({
                    url: `{{ url('issues') }}/${issueId}`,
                    method: 'POST',
                    data: {
                        _method: 'DELETE',
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        // Remove the row from the table with animation
                        $row.fadeOut(300, function() {
                            $(this).remove();

                            // Check if there are no more issues
                            if ($('.issues-table tbody tr').length === 0) {
                                $('.issues-table tbody').html(`
                                    <tr>
                                        <td colspan="9" class="text-center">
                                            <div class="alert alert-light">No issues found for this project.</div>
                                        </td>
                                    </tr>
                                `);
                            }
                        });

                        // Show success message
                        if (typeof toastr !== 'undefined') {
                            toastr.success('Issue deleted successfully');
                        } else {
                            Toast.fire({
                                icon: 'success',
                                title: 'Issue deleted successfully'
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Delete error details:', {
                            status: xhr.status,
                            statusText: xhr.statusText,
                            responseText: xhr.responseText,
                            error: error
                        });

                        let errorMessage = 'Error deleting issue';
                        try {
                            if (xhr.responseJSON) {
                                errorMessage = xhr.responseJSON.message || errorMessage;
                            } else if (xhr.responseText) {
                                const response = JSON.parse(xhr.responseText);
                                errorMessage = response.message || errorMessage;
                            }
                        } catch (e) {
                            console.error('Error parsing response:', e);
                        }

                        // Reset button state
                        $button.prop('disabled', false);
                        $button.html('<i class="fa fa-trash"></i>');

                        // Show error message
                        if (typeof toastr !== 'undefined') {
                            toastr.error(errorMessage);
                        } else {
                            Toast.fire({
                                icon: 'error',
                                title: errorMessage
                            });
                        }
                    }
                });
            }
        });
    });
</script>

@endsection
