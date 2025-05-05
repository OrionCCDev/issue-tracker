<div class=" container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="card-header">Project Details</div>
                    <button class="btn btn-sm btn-outline-secondary toggle-stats" data-toggle="collapse" data-target="#projectDataDetails">
                        <i class="fa fa-chevron-down"></i>
                    </button>
                </div>
                <div class="card-body collapse show" id="projectDataDetails">
                    <form id="projectDetailsForm" method="POST" action="{{ route('projects.update', $project) }}">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="name" class="col-form-label">Project Name</label>
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $project->name) }}" required>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="code" class="col-form-label">Project Code</label>
                                    <input id="code" type="text" class="form-control @error('code') is-invalid @enderror" name="code" value="{{ old('code', $project->code) }}" required>
                                    @error('code')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="manager_id" class="col-form-label">Project Manager</label>
                                    <select id="manager_id" class="form-control @error('manager_id') is-invalid @enderror" name="manager_id" required>
                                        <option value="">Select a manager</option>
                                        @foreach($managers as $manager)
                                            <option value="{{ $manager->id }}" {{ old('manager_id', $project->manager_id) == $manager->id ? 'selected' : '' }}>
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
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="duration" class="col-form-label">Duration (Months)</label>
                                    <input id="duration" type="number" class="form-control @error('duration') is-invalid @enderror" name="duration" value="{{ old('duration', $project->duration) }}">
                                    @error('duration')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="commencement_date" class="col-form-label">Commencement Date</label>
                                    <input id="commencement_date" type="date" class="form-control @error('commencement_date') is-invalid @enderror" name="commencement_date" value="{{ old('commencement_date', $project->commencement_date ? $project->commencement_date->format('Y-m-d') : '') }}">
                                    @error('commencement_date')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="completion_date" class="col-form-label">Completion Date</label>
                                    <input id="completion_date" type="date" class="form-control @error('completion_date') is-invalid @enderror" name="completion_date" value="{{ old('completion_date', $project->completion_date ? $project->completion_date->format('Y-m-d') : '') }}">
                                    @error('completion_date')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="total_billed" class="col-form-label">Total Billed</label>
                                    <input id="total_billed" type="number" step="0.01" class="form-control @error('total_billed') is-invalid @enderror" name="total_billed" value="{{ old('total_billed', $project->total_billed) }}" onchange="calculateRemainingUnbilled()" oninput="calculateRemainingUnbilled()">
                                    @error('total_billed')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="remaining_unbilled" class="col-form-label">Remaining Unbilled</label>
                                    <input id="remaining_unbilled" type="number" step="0.01" class="form-control @error('remaining_unbilled') is-invalid @enderror bg-light" name="remaining_unbilled" value="{{ old('remaining_unbilled', $project->remaining_unbilled) }}" readonly>
                                    @error('remaining_unbilled')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="planned_percentage" class="col-form-label">Planned Value</label>
                                    <input id="planned_percentage" type="number" step="0.01" class="form-control @error('planned_percentage') is-invalid @enderror" name="planned_percentage" value="{{ old('planned_percentage', $project->planned_percentage) }}">
                                    @error('planned_percentage')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="actual_percentage" class="col-form-label">Actual Value</label>
                                    <input id="actual_percentage" type="number" step="0.01" class="form-control @error('actual_percentage') is-invalid @enderror" name="actual_percentage" value="{{ old('actual_percentage', $project->actual_percentage) }}">
                                    @error('actual_percentage')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="variance_percentage" class="col-form-label">Variance</label>
                                    <input id="variance_percentage" type="number" step="0.01" class="form-control @error('variance_percentage') is-invalid @enderror" name="variance_percentage" value="{{ old('variance_percentage', $project->variance_percentage) }}">
                                    @error('variance_percentage')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3 form-group">
                                <label for="expected_invoice_month" class="col-form-label">Expected Invoice Month</label>
                                <select id="expected_invoice_month" class="form-control @error('expected_invoice_month') is-invalid @enderror" name="expected_invoice_month">
                                    <option value="">Select Month</option>
                                    @for($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}" {{ old('expected_invoice_month', $project->expected_invoice_month) == $i ? 'selected' : '' }}>
                                            {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                                        </option>
                                    @endfor
                                </select>
                                @error('expected_invoice_month')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="time_elapsed" class="col-form-label">Time Elapsed (days)</label>
                                    <input id="time_elapsed" type="text" class="form-control @error('time_elapsed') is-invalid @enderror" name="time_elapsed" value="{{ old('time_elapsed', $project->time_elapsed) }}">
                                    @error('time_elapsed')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="time_balance" class="col-form-label">Time Balance (days)</label>
                                    <input id="time_balance" type="number" class="form-control @error('time_balance') is-invalid @enderror" name="time_balance" value="{{ old('time_balance', $project->time_balance) }}">
                                    @error('time_balance')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="variation_status" class="col-form-label">Variation Status</label>
                                    <input id="variation_status" type="text" class="form-control @error('variation_status') is-invalid @enderror" name="variation_status" value="{{ old('variation_status', $project->variation_status) }}">
                                    @error('variation_status')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="variation_number" class="col-form-label">Variation Number</label>
                                    <input id="variation_number" type="number" class="form-control @error('variation_number') is-invalid @enderror" name="variation_number" value="{{ old('variation_number', $project->variation_number) }}" onchange="calculateRemainingUnbilled()" oninput="calculateRemainingUnbilled()">
                                    @error('variation_number')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="eot_status" class="col-form-label">EOT Status</label>
                                    <input id="eot_status" type="text" class="form-control @error('eot_status') is-invalid @enderror" name="eot_status" value="{{ old('eot_status', $project->eot_status) }}">
                                    @error('eot_status')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="ncrs_status" class="col-form-label">NCRs Status</label>
                                    <input id="ncrs_status" type="text" class="form-control @error('ncrs_status') is-invalid @enderror" name="ncrs_status" value="{{ old('ncrs_status', $project->ncrs_status) }}">
                                    @error('ncrs_status')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="current_invoice_status" class="col-form-label">Current Invoice Status</label>
                                    <input id="current_invoice_status" type="text" class="form-control @error('current_invoice_status') is-invalid @enderror" name="current_invoice_status" value="{{ old('current_invoice_status', $project->current_invoice_status) }}">
                                    @error('current_invoice_status')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="current_invoice_value" class="col-form-label">Current Invoice Value</label>
                                    <input id="current_invoice_value" type="number" step="0.01" class="form-control @error('current_invoice_value') is-invalid @enderror" name="current_invoice_value" value="{{ old('current_invoice_value', $project->current_invoice_value) }}">
                                    @error('current_invoice_value')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="expected_invoice" class="col-form-label">Expected Invoice</label>
                                    <input id="expected_invoice" type="text" class="form-control @error('expected_invoice') is-invalid @enderror" name="expected_invoice" value="{{ old('expected_invoice', $project->expected_invoice) }}">
                                    @error('expected_invoice')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="expected_invoice_date" class="col-form-label">Expected Invoice Date</label>
                                    <input id="expected_invoice_date" type="date" class="form-control @error('expected_invoice_date') is-invalid @enderror" name="expected_invoice_date" value="{{ old('expected_invoice_date', $project->expected_invoice_date ? $project->expected_invoice_date->format('Y-m-d') : '') }}">
                                    @error('expected_invoice_date')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="previous_invoice_status" class="col-form-label">Previous Invoice Status</label>
                                    <input id="previous_invoice_status" type="text" class="form-control @error('previous_invoice_status') is-invalid @enderror" name="previous_invoice_status" value="{{ old('previous_invoice_status', $project->previous_invoice_status) }}">
                                    @error('previous_invoice_status')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="previous_invoice_value" class="col-form-label">Previous Invoice Value</label>
                                    <input id="previous_invoice_value" type="number" step="0.01" class="form-control @error('previous_invoice_value') is-invalid @enderror" name="previous_invoice_value" value="{{ old('previous_invoice_value', $project->previous_invoice_value) }}">
                                    @error('previous_invoice_value')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="description" class="col-form-label">Description</label>
                                    <textarea id="description" class="form-control @error('description') is-invalid @enderror" name="description" rows="4">{{ old('description', $project->description) }}</textarea>
                                    @error('description')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="project_value" class="col-form-label">Project Value</label>
                                    <input id="project_value" type="number" step="0.01" class="form-control @error('project_value') is-invalid @enderror" name="project_value" value="{{ old('project_value', $project->project_value) }}" onchange="calculateRemainingUnbilled()" oninput="calculateRemainingUnbilled()">
                                    @error('project_value')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <!-- Submit Button -->
                                <div class="form-group mt-4 text-right">
                                    @if(Auth::user()->role === 'o-admin' || Auth::user()->role === 'cm' || (Auth::user()->role === 'pm' && $project->manager_id === Auth::id()))
                                    <button type="button" class="btn btn-secondary" onclick="resetForm()">Reset</button>
                                    <button type="submit" class="btn btn-primary updateBtn">
                                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                        <span class="button-text">Update Project</span>
                                    </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@section('custom_js')
<script>
function calculateTimeBalance() {
    const completionDate = new Date(document.getElementById('completion_date').value);
    const today = new Date();

    // Reset hours to midnight for accurate day calculation
    today.setHours(0, 0, 0, 0);
    completionDate.setHours(0, 0, 0, 0);

    // Calculate difference in days
    const diffTime = completionDate - today;
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

    // Update the time balance input
    const timeBalanceInput = document.getElementById('time_balance');
    timeBalanceInput.value = diffDays;

    // Change background color based on time balance
    if (diffDays < 0) {
        timeBalanceInput.classList.add('bg-danger', 'text-white');
        timeBalanceInput.classList.remove('bg-light');
    } else {
        timeBalanceInput.classList.remove('bg-danger', 'text-white');
        timeBalanceInput.classList.add('bg-light');
    }
}

function calculateVariance() {
    const plannedPercentage = parseFloat(document.getElementById('planned_percentage').value) || 0;
    const actualPercentage = parseFloat(document.getElementById('actual_percentage').value) || 0;
    const variancePercentage = plannedPercentage - actualPercentage;

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

// Calculate initial values
document.addEventListener('DOMContentLoaded', function() {
    calculateVariance();
    calculateRemainingUnbilled();
    calculateTimeBalance();
});

// Calculate on input to make it more responsive
document.getElementById('planned_percentage').addEventListener('input', calculateVariance);
document.getElementById('actual_percentage').addEventListener('input', calculateVariance);
document.getElementById('project_value').addEventListener('input', calculateRemainingUnbilled);
document.getElementById('total_billed').addEventListener('input', calculateRemainingUnbilled);
document.getElementById('completion_date').addEventListener('change', calculateTimeBalance);

// Add AJAX form submission
$(document).ready(function() {
    let isSubmitting = false;

    $('#projectDetailsForm').on('submit', function(e) {
        e.preventDefault();

        if (isSubmitting) return;

        const $form = $(this);
        const $submitBtn = $('.updateBtn');
        const $buttonText = $submitBtn.find('.button-text');
        const $spinner = $submitBtn.find('.spinner-border');

        // Show loading state
        isSubmitting = true;
        $submitBtn.prop('disabled', true);
        $buttonText.addClass('d-none');
        $spinner.removeClass('d-none');

        // Calculate time balance before submission
        calculateTimeBalance();

        // Collect form data
        const formData = new FormData(this);

        // Send AJAX request
        $.ajax({
            url: $form.attr('action'),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                // Reset button state first
                resetButtonState();
                // Then show success message
                toastr.success('Project details updated successfully');
            },
            error: function(xhr) {
                // Reset button state first
                resetButtonState();
                // Then show error message
                toastr.error(xhr.responseJSON.message || 'Error updating project details');

                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    Object.keys(errors).forEach(field => {
                        const $input = $(`[name="${field}"]`);
                        $input.addClass('is-invalid');
                        $input.next('.invalid-feedback').text(errors[field][0]);
                    });
                }
            }
        });

        function resetButtonState() {
            isSubmitting = false;
            $submitBtn.prop('disabled', false);
            $buttonText.removeClass('d-none');
            $spinner.addClass('d-none');
        }
    });

    // Remove validation classes when input changes
    $('input, select, textarea').on('input change', function() {
        $(this).removeClass('is-invalid');
        $(this).next('.invalid-feedback').text('');
    });
});
</script>
@endsection
