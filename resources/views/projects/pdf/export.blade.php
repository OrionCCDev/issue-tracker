<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $project->name }} - Project Report</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .company-header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #45bddb;
        }
        .company-logo {
            max-width: 200px;
            margin-bottom: 20px;
        }
        .company-description {
            text-align: center;
            margin: 20px 0;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 5px;
            font-style: italic;
            color: #666;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #45bddb;
        }
        .header h1 {
            color: #45bddb;
            margin-bottom: 5px;
        }
        .header p {
            margin: 5px 0;
        }
        .project-info {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 30px;
        }
        .project-info h2 {
            color: #45bddb;
            margin-bottom: 15px;
        }
        .info-row {
            display: flex;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        .info-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        .info-item {
            flex: 1;
            padding: 0 10px;
        }
        {{--  .info-item:first-child {
            padding-left: 0;
        }
        .info-item:last-child {
            padding-right: 0;
        }  --}}
        .info-label {
            font-weight: bold;
            color: #666;
            display: block;
            margin-bottom: 5px;
        }
        .info-value {
            color: #333;
        }
        .section {
            margin-bottom: 30px;
            page-break-inside: avoid;
        }
        .section h2 {
            color: #45bddb;
            margin-bottom: 15px;
            padding-bottom: 5px;
            border-bottom: 1px solid #45bddb;
        }
        .issues-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .issues-table th {
            background-color: #45bddb;
            color: white;
            padding: 12px;
            text-align: left;
        }
        .issues-table td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        .issues-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .status-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            display: block;

        }
        .status-open { background-color: #ffeeba; color: #856404; }
        .status-in_progress { background-color: #b8daff; color: #004085; }
        .status-resolved { background-color: #c3e6cb; color: #155724; }
        .priority-badge {
            padding: 4px 8px;
            display: block;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }
        .priority-low { background-color: #d4edda; color: #155724; }
        .priority-medium { background-color: #fff3cd; color: #856404; }
        .priority-high { background-color: #f8d7da; color: #721c24; }
        .priority-critical { background-color: #dc3545; color: white; }
        .footer {
            text-align: center;
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            color: #666;
            font-size: 12px;
            display: block;

        }
        .description-box {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="company-header">
        <div class="company-description">
            <p>This report has been generated using Orion Contracting Company's Issue Tracker System, a comprehensive project management tool designed to monitor, track, and resolve project-related issues efficiently. The system provides real-time insights into project progress, issue status, and team assignments, ensuring effective project delivery and client satisfaction.</p>
        </div>
    </div>

    <div class="header">
        <h1>{{ $project->name }}</h1>
        <p>Project Code: {{ $project->code }}</p>
        <p>Project Manager: {{ $project->manager->name ?? 'Not assigned' }}</p>
        <p>Generated on: {{ now()->format('F j, Y') }}</p>
    </div>

    <div class="section">
        <h2>Project Details</h2>
        <div class="project-info">
            <!-- Row 1: Duration and Dates -->
            <div class="info-row">
                <div class="info-item">
                    <span class="info-label">Duration (days)</span>
                    <span class="info-value">{{ $project->duration ? $project->duration . ' days' : 'Not set' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Commencement Date</span>
                    <span class="info-value">{{ $project->commencement_date ? $project->commencement_date->format('F j, Y') : 'Not set' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Completion Date</span>
                    <span class="info-value">{{ $project->completion_date ? $project->completion_date->format('F j, Y') : 'Not set' }}</span>
                </div>
            </div>

            <!-- Row 2: Financial Overview -->
            <div class="info-row">
                <div class="info-item">
                    <span class="info-label">Project Value</span>
                    <span class="info-value">{{ $project->project_value ? number_format($project->project_value, 2) : 'Not set' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Total Billed</span>
                    <span class="info-value">{{ $project->total_billed ? number_format($project->total_billed, 2) : 'Not set' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Remaining Unbilled</span>
                    <span class="info-value">{{ $project->remaining_unbilled ? number_format($project->remaining_unbilled, 2) : 'Not set' }}</span>
                </div>
            </div>

            <!-- Row 3: Value Analysis -->
            <div class="info-row">
                <div class="info-item">
                    <span class="info-label">Planned Value</span>
                    <span class="info-value">{{ $project->planned_value ? number_format($project->planned_value, 2) : 'Not set' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Actual Value</span>
                    <span class="info-value">{{ $project->actual_value ? number_format($project->actual_value, 2) : 'Not set' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Variance</span>
                    <span class="info-value">{{ $project->variance ? number_format($project->variance, 2) : 'Not set' }}</span>
                </div>
            </div>

            <!-- Row 4: Expected Invoice -->
            <div class="info-row">
                <div class="info-item">
                    <span class="info-label">Expected Invoice Month</span>
                    <span class="info-value">{{ $project->expected_invoice_month ?? 'Not set' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Expected Invoice Date</span>
                    <span class="info-value">{{ $project->expected_invoice_date ? $project->expected_invoice_date->format('F j, Y') : 'Not set' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Expected Invoice</span>
                    <span class="info-value">{{ $project->expected_invoice ? number_format($project->expected_invoice, 2) : 'Not set' }}</span>
                </div>
            </div>

            <!-- Row 5: Time Tracking -->
            <div class="info-row">
                <div class="info-item">
                    <span class="info-label">Time Elapsed (days)</span>
                    <span class="info-value">{{ $project->time_elapsed ?? 'Not set' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Time Balance (days)</span>
                    <span class="info-value">{{ $project->time_balance ?? 'Not set' }}</span>
                </div>
            </div>

            <!-- Row 6: Variation -->
            <div class="info-row">
                <div class="info-item">
                    <span class="info-label">Variation Status</span>
                    <span class="info-value">{{ $project->variation_status ?? 'Not set' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Variation Number</span>
                    <span class="info-value">{{ $project->variation_number ?? 'Not set' }}</span>
                </div>
            </div>

            <!-- Row 7: EOT and NCRs -->
            <div class="info-row">
                <div class="info-item">
                    <span class="info-label">EOT Status</span>
                    <span class="info-value">{{ $project->eot_status ?? 'Not set' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">NCRs Status</span>
                    <span class="info-value">{{ $project->ncrs_status ?? 'Not set' }}</span>
                </div>
            </div>

            <!-- Row 8: Current and Previous Invoice -->
            <div class="info-row">
                <div class="info-item">
                    <span class="info-label">Current Invoice Status</span>
                    <span class="info-value">{{ $project->current_invoice_status ?? 'Not set' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Current Invoice Value</span>
                    <span class="info-value">{{ $project->current_invoice_value ? number_format($project->current_invoice_value, 2) : 'Not set' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Previous Invoice Status</span>
                    <span class="info-value">{{ $project->previous_invoice_status ?? 'Not set' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Previous Invoice Value</span>
                    <span class="info-value">{{ $project->previous_invoice_value ? number_format($project->previous_invoice_value, 2) : 'Not set' }}</span>
                </div>
            </div>

            <!-- Row 9: Description -->
            <div class="info-row">
                <div class="info-item" style="flex: 1 1 100%;">
                    <span class="info-label">Description</span>
                    <div class="description-box">
                        {{ $project->description ?? 'No description provided' }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="section">
        <h2>Issues Overview</h2>
        <table class="issues-table">
            <tbody>
                @forelse($issues as $issue)
                <tr>
                    <td colspan="6" style="background-color: #f8f9fa;">
                        <strong>Title:</strong> {{ $issue->title }}
                    </td>
                </tr>
                <tr>
                    <td colspan="6">
                        <strong>Description:</strong> {{ $issue->description ?? 'No description provided' }}
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center;">
                        <div style="display: flex; flex-direction: column; align-items: center;">
                            <strong>Status</strong>
                            <span class="status-badge status-{{ $issue->status }}">
                                {{ ucfirst(str_replace('_', ' ', $issue->status)) }}
                            </span>
                        </div>
                    </td>
                    <td style="text-align: center;">
                        <div style="display: flex; flex-direction: column; align-items: center;">
                            <strong>Priority</strong>
                            <span class="priority-badge priority-{{ $issue->priority }}">
                                {{ ucfirst($issue->priority) }}
                            </span>
                        </div>
                    </td>
                    <td colspan="2" style="text-align: center;">
                        <div style="display: flex; flex-direction: column; align-items: center;">
                            <strong>Assignees</strong>
                            <div>
                                @foreach($issue->assignees as $assignee)
                                    {{ $assignee->name }}@if(!$loop->last), @endif
                                @endforeach
                            </div>
                        </div>
                    </td>
                    <td style="text-align: center;">
                        <div style="display: flex; flex-direction: column; align-items: center;">
                            <strong>Target Date</strong>
                            <div>{{ $issue->target_resolution_date ? $issue->target_resolution_date->format('Y-m-d') : '-' }}</div>
                        </div>
                    </td>
                    <td style="text-align: center;">
                        <div style="display: flex; flex-direction: column; align-items: center;">
                            <strong>Actual Date</strong>
                            <div>{{ $issue->actual_resolution_date ? $issue->actual_resolution_date->format('Y-m-d') : '-' }}</div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="6" style="height: 10px;"></td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center;">No issues found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p>Generated by Issue Tracker System</p>
    </div>
</body>
</html>