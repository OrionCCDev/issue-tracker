@extends('layouts.app')

@section('content')
<div class=" container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Create New Project</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('projects.store') }}">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <!-- Basic Information -->
                                <h5 class="mb-3">Basic Information</h5>

                                <div class="form-group">
                                    <label for="name">Project Name</label>
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="code">Project Code</label>
                                    <input id="code" type="text" class="form-control @error('code') is-invalid @enderror" name="code" value="{{ old('code') }}" required>
                                    @error('code')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea id="description" class="form-control @error('description') is-invalid @enderror" name="description">{{ old('description') }}</textarea>
                                    @error('description')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="manager_id">Project Manager</label>
                                    <select id="manager_id" class="form-control @error('manager_id') is-invalid @enderror" name="manager_id" required>
                                        <option value="">Select a manager</option>
                                        @foreach($managers as $manager)
                                            <option value="{{ $manager->id }}" {{ old('manager_id') == $manager->id ? 'selected' : '' }}>
                                                {{ $manager->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('manager_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <!-- Status Information -->
                                <h5 class="mb-3">Status Information</h5>

                                <div class="form-group">
                                    <label for="variation_status">Variation Status</label>
                                    <input id="variation_status" type="text" class="form-control @error('variation_status') is-invalid @enderror" name="variation_status" value="{{ old('variation_status') }}">
                                    @error('variation_status')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="variation_amount">Variation Amount</label>
                                    <input id="variation_amount" type="number" step="0.01" class="form-control @error('variation_amount') is-invalid @enderror" name="variation_amount" value="{{ old('variation_amount', 0) }}" onchange="calculateRemainingUnbilled()" oninput="calculateRemainingUnbilled()">
                                    @error('variation_amount')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="eot_status">EOT Status</label>
                                    <input id="eot_status" type="text" class="form-control @error('eot_status') is-invalid @enderror" name="eot_status" value="{{ old('eot_status') }}">
                                    @error('eot_status')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="ncrs_status">NCRs Status</label>
                                    <input id="ncrs_status" type="text" class="form-control @error('ncrs_status') is-invalid @enderror" name="ncrs_status" value="{{ old('ncrs_status') }}">
                                    @error('ncrs_status')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <h5 class="mb-3 mt-4">Expected Invoice</h5>

                                <div class="form-group">
                                    <label for="expected_invoice_date">Expected Invoice Date</label>
                                    <input id="expected_invoice_date" type="date" class="form-control @error('expected_invoice_date') is-invalid @enderror" name="expected_invoice_date" value="{{ old('expected_invoice_date') }}">
                                    @error('expected_invoice_date')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="expected_invoice_month">Expected Invoice Month</label>
                                    <input id="expected_invoice_month" type="text" class="form-control @error('expected_invoice_month') is-invalid @enderror" name="expected_invoice_month" value="{{ old('expected_invoice_month') }}">
                                    @error('expected_invoice_month')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="expected_invoice">Expected Invoice Month</label>
                                    <input id="expected_invoice" type="text" class="form-control @error('expected_invoice') is-invalid @enderror" name="expected_invoice" value="{{ old('expected_invoice') }}">
                                    @error('expected_invoice')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <h5 class="mb-3 mt-4">Previous Invoice</h5>

                                <div class="form-group">
                                    <label for="previous_invoice_status">Previous Invoice Status</label>
                                    <input id="previous_invoice_status" type="text" class="form-control @error('previous_invoice_status') is-invalid @enderror" name="previous_invoice_status" value="{{ old('previous_invoice_status') }}">
                                    @error('previous_invoice_status')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="previous_invoice_value">Previous Invoice Value</label>
                                    <input id="previous_invoice_value" type="number" step="0.01" class="form-control @error('previous_invoice_value') is-invalid @enderror" name="previous_invoice_value" value="{{ old('previous_invoice_value', 0) }}">
                                    @error('previous_invoice_value')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="previous_invoice_month">Previous Invoice Month</label>
                                    <input id="previous_invoice_month" type="text" class="form-control @error('previous_invoice_month') is-invalid @enderror" name="previous_invoice_month" value="{{ old('previous_invoice_month') }}">
                                    @error('previous_invoice_month')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                            </div>

                            <div class="col-md-6">
                                <!-- Timeline Information -->
                                <h5 class="mb-3 ">Timeline Information</h5>

                                <div class="form-group">
                                    <label for="project_value">Project Value</label>
                                    <input id="project_value" type="number" step="0.01" class="form-control @error('project_value') is-invalid @enderror" name="project_value" value="{{ old('project_value', 0) }}" onchange="calculateRemainingUnbilled()" oninput="calculateRemainingUnbilled()">
                                    @error('project_value')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="duration">Duration (Months)</label>
                                    <input id="duration" type="number" class="form-control @error('duration') is-invalid @enderror" name="duration" value="{{ old('duration') }}">
                                    @error('duration')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="commencement_date">Commencement Date</label>
                                    <input id="commencement_date" type="date" class="form-control @error('commencement_date') is-invalid @enderror" name="commencement_date" value="{{ old('commencement_date') }}">
                                    @error('commencement_date')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="completion_date">Completion Date</label>
                                    <input id="completion_date" type="date" class="form-control @error('completion_date') is-invalid @enderror" name="completion_date" value="{{ old('completion_date') }}">
                                    @error('completion_date')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <!-- Financial Information -->
                                <h5 class="mb-3">Financial Information</h5>

                                <div class="form-group">
                                    <label for="total_billed">Total Billed</label>
                                    <input id="total_billed" type="number" step="0.01" class="form-control @error('total_billed') is-invalid @enderror" name="total_billed" value="{{ old('total_billed', 0) }}" onchange="calculateRemainingUnbilled()" oninput="calculateRemainingUnbilled()">
                                    @error('total_billed')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="remaining_unbilled">Remaining Unbilled</label>
                                    <input id="remaining_unbilled" type="number" step="0.01" class="form-control @error('remaining_unbilled') is-invalid @enderror bg-light" name="remaining_unbilled" value="{{ old('remaining_unbilled', 0) }}" readonly>
                                    @error('remaining_unbilled')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="planned_percentage">Planned Percentage</label>
                                    <input id="planned_percentage" type="number" step="0.01" class="form-control @error('planned_percentage') is-invalid @enderror" name="planned_percentage" value="{{ old('planned_percentage', 0) }}" onchange="calculateVariance()">
                                    @error('planned_percentage')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="actual_percentage">Actual Percentage</label>
                                    <input id="actual_percentage" type="number" step="0.01" class="form-control @error('actual_percentage') is-invalid @enderror" name="actual_percentage" value="{{ old('actual_percentage', 0) }}" onchange="calculateVariance()">
                                    @error('actual_percentage')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="variance_percentage">Variance Percentage</label>
                                    <input id="variance_percentage" type="number" step="0.01" class="form-control @error('variance_percentage') is-invalid @enderror bg-light" name="variance_percentage" value="{{ old('variance_percentage', 0) }}" readonly>
                                    @error('variance_percentage')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <!-- Time Tracking -->
                                <h5 class="mb-3 mt-4">Time Tracking</h5>

                                <div class="form-group">
                                    <label for="time_elapsed">Time Elapsed (Days)</label>
                                    <input id="time_elapsed" type="number" class="form-control @error('time_elapsed') is-invalid @enderror bg-light" name="time_elapsed" value="{{ old('time_elapsed', 0) }}" readonly>
                                    @error('time_elapsed')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="time_balance">Time Balance (Days)</label>
                                    <input id="time_balance" type="number" class="form-control @error('time_balance') is-invalid @enderror bg-light" name="time_balance" value="{{ old('time_balance', 0) }}" readonly>
                                    @error('time_balance')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>


                                <!-- Invoice Information -->
                                <h5 class="mb-3 mt-4">Current Invoice</h5>

                                <div class="form-group">
                                    <label for="current_invoice_status">Current Invoice Status</label>
                                    <input id="current_invoice_status" type="text" class="form-control @error('current_invoice_status') is-invalid @enderror" name="current_invoice_status" value="{{ old('current_invoice_status') }}">
                                    @error('current_invoice_status')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="current_invoice_value">Current Invoice Value</label>
                                    <input id="current_invoice_value" type="number" step="0.01" class="form-control @error('current_invoice_value') is-invalid @enderror" name="current_invoice_value" value="{{ old('current_invoice_value', 0) }}">
                                    @error('current_invoice_value')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="current_invoice_month">Current Invoice Month</label>
                                    <input id="current_invoice_month" type="text" class="form-control @error('current_invoice_month') is-invalid @enderror" name="current_invoice_month" value="{{ old('current_invoice_month') }}">
                                    @error('current_invoice_month')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>



                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-primary">
                                    Create Project
                                </button>
                                <a href="{{ route('projects.index') }}" class="btn btn-secondary">
                                    Cancel
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.form-group {
    margin-bottom: 1rem;
}
.card-body {
    padding: 2rem;
}
h5 {
    color: #0f5874;
    font-weight: 600;
}
</style>
@endsection

@section('custom_js')
<script>
function calculateVariance() {
    const plannedPercentage = parseFloat(document.getElementById('planned_percentage').value) || 0;
    const actualPercentage = parseFloat(document.getElementById('actual_percentage').value) || 0;
    const variancePercentage = actualPercentage - plannedPercentage;

    const varianceInput = document.getElementById('variance_percentage');
    varianceInput.value = variancePercentage.toFixed(2);

    // Change background color based on variance
    if (variancePercentage < 0) {
        varianceInput.classList.add('bg-danger', 'text-white');
        varianceInput.classList.remove('bg-light');
    } else {
        varianceInput.classList.remove('bg-danger', 'text-white');
        varianceInput.classList.add('bg-light');
    }
}

function calculateRemainingUnbilled() {
    const projectValue = parseFloat(document.getElementById('project_value').value) || 0;
    const totalBilled = parseFloat(document.getElementById('total_billed').value) || 0;
    const remainingUnbilled = projectValue - totalBilled;

    const remainingUnbilledInput = document.getElementById('remaining_unbilled');
    remainingUnbilledInput.value = remainingUnbilled.toFixed(2);

    // Change background color based on remaining unbilled
    if (remainingUnbilled < 0) {
        remainingUnbilledInput.classList.add('bg-danger', 'text-white');
        remainingUnbilledInput.classList.remove('bg-light');
    } else {
        remainingUnbilledInput.classList.remove('bg-danger', 'text-white');
        remainingUnbilledInput.classList.add('bg-light');
    }
}

function calculateTimeElapsedAndBalance() {
    const commencementDate = document.getElementById('commencement_date').value;
    const completionDate = document.getElementById('completion_date').value;

    if (commencementDate && completionDate) {
        // Calculate days between dates
        const start = new Date(commencementDate);
        const end = new Date(completionDate);
        const today = new Date();

        // Total project duration in days
        const totalDays = Math.round((end - start) / (1000 * 60 * 60 * 24));

        // Time elapsed so far (capped at total days)
        let elapsedDays = Math.round((today - start) / (1000 * 60 * 60 * 24));
        elapsedDays = Math.max(0, Math.min(elapsedDays, totalDays));

        // Time balance
        const balanceDays = totalDays - elapsedDays;

        document.getElementById('time_elapsed').value = elapsedDays;
        document.getElementById('time_balance').value = balanceDays;
    }
}

// Calculate initial values
document.addEventListener('DOMContentLoaded', function() {
    calculateVariance();
    calculateRemainingUnbilled();
    calculateTimeElapsedAndBalance();
});

// Calculate on input to make it more responsive
document.getElementById('planned_percentage').addEventListener('input', calculateVariance);
document.getElementById('actual_percentage').addEventListener('input', calculateVariance);
document.getElementById('project_value').addEventListener('input', calculateRemainingUnbilled);
document.getElementById('total_billed').addEventListener('input', calculateRemainingUnbilled);
document.getElementById('commencement_date').addEventListener('change', calculateTimeElapsedAndBalance);
document.getElementById('completion_date').addEventListener('change', calculateTimeElapsedAndBalance);
</script>
@endsection
