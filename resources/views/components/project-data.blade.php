@props(['project'])

<div class="project-data">
    <div class="row">
        <div class="col-md-6">
            <table class="table table-bordered bg-light">
                <tr class="bg-primary text-white">
                    <th>Project Value:</th>
                    <td class="text-right">{{ number_format($project->project_value, 2) }}</td>
                </tr>
                <tr class="bg-primary text-white">
                    <th>Project Duration:</th>
                    <td class="text-right">{{ $project->duration }} Months</td>
                </tr>
                <tr class="bg-primary text-white">
                    <th>Commencement Date:</th>
                    <td class="text-right">{{ $project->commencement_date ? $project->commencement_date->format('d-M-y') : 'N/A' }}</td>
                </tr>
                <tr class="bg-primary text-white">
                    <th>Completion Date:</th>
                    <td class="text-right">{{ $project->completion_date ? $project->completion_date->format('d-M-y') : 'N/A' }}</td>
                </tr>
            </table>

            <table class="table table-bordered mt-4 bg-light">
                <tr class="bg-primary text-white">
                    <th>Total Billed:</th>
                    <td class="text-right">{{ number_format($project->total_billed, 2) }}</td>
                </tr>
                <tr class="bg-primary text-white">
                    <th>Remaining unbilled:</th>
                    <td class="text-right">{{ number_format($project->remaining_unbilled, 2) }}</td>
                </tr>
                <tr class="bg-primary text-white">
                    <th>Planned:</th>
                    <td class="text-right">{{ number_format($project->planned_percentage, 2) }}%</td>
                </tr>
                <tr class="bg-primary text-white">
                    <th>actual:</th>
                    <td class="text-right">{{ number_format($project->actual_percentage, 2) }}%</td>
                </tr>
                <tr @if($project->variance_percentage < 0) class="bg-danger text-white" @else class="bg-primary text-white" @endif>
                    <th>Variance:</th>
                    <td class="text-right">{{ number_format($project->variance_percentage, 2) }}%</td>
                </tr>
                <tr class="bg-primary text-white">
                    <th>Time Elapsed:</th>
                    <td class="text-right">{{ $project->time_elapsed }}</td>
                </tr>
                <tr class="bg-primary text-white">
                    <th>Time Balance:</th>
                    <td class="text-right">{{ $project->time_balance }}</td>
                </tr>
            </table>
        </div>

        <div class="col-md-6">
            <table class="table table-bordered bg-light">
                <tr class="bg-primary text-white">
                    <th>Variation status:</th>
                    <td>{{ $project->variation_status }}</td>
                    <td class="text-right">{{ number_format($project->variation_number, 2) }}</td>
                </tr>
                <tr class="bg-primary text-white">
                    <th>EOT status:</th>
                    <td colspan="2">{{ $project->eot_status }}</td>
                </tr>
                <tr class="bg-primary text-white">
                    <th>NCRs Status:</th>
                    <td colspan="2">{{ $project->ncrs_status }}</td>
                </tr>
            </table>

            <table class="table table-bordered mt-4 bg-light">
                <tr class="bg-primary text-white">
                    <th>Current Invoice Status:</th>
                    <td colspan="2">{{ $project->current_invoice_status }}</td>
                </tr>
                <tr class="bg-primary text-white">
                    <th>Current Invoice value:</th>
                    <td>{{ $project->current_invoice_month_name }}</td>
                    <td class="text-right">{{ number_format($project->current_invoice_value, 2) }}</td>
                </tr>
                <tr class="bg-primary text-white">
                    <th>Previous Invoice Status:</th>
                    <td colspan="2">{{ $project->previous_invoice_status }}</td>
                </tr>
                <tr class="bg-primary text-white">
                    <th>Previous Invoice Value:</th>
                    <td>{{ $project->previous_invoice_month_name }}</td>
                    <td class="text-right">{{ number_format($project->previous_invoice_value, 2) }}</td>
                </tr>
                <tr class="bg-primary text-white">
                    <th>Expected invoice:</th>
                    <td>{{ $project->expected_invoice_month_name }}</td>
                    <td class="text-right">{{ $project->expected_invoice_date ? $project->expected_invoice_date->format('d-M-y') : 'under preparation' }}</td>
                </tr>
            </table>
        </div>
    </div>
</div>

<style>
.project-data .table {
    margin-bottom: 0;
}
.project-data .table th {
    width: 40%;
    background-color: #0f5874 !important;
    color: white;
}
.project-data .table td {
    background-color: #f8f9fa;
    color: #0f5874;
}
.project-data .text-right {
    text-align: right;
}
.project-data .bg-primary {
    background-color: #0f5874 !important;
}
.project-data .bg-danger {
    background-color: #dc3545 !important;
}
</style>
