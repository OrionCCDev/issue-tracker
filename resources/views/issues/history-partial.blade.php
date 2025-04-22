@php
    // Group history entries by date
    $historyByDate = $issue->history->groupBy(function($item) {
        return $item->created_at->format('Y-m-d');
    })->sortKeysDesc(); // Sort by date descending (newest first)
@endphp

<div class="accordion" id="issueHistoryAccordion">
    @forelse($historyByDate as $date => $entries)
        <div class="card mb-2">
            <div class="card-header py-2" id="heading{{ \Illuminate\Support\Str::slug($date) }}">
                <h2 class="mb-0">
                    <button class="btn btn-link btn-block text-left d-flex justify-content-between align-items-center collapsed"
                            type="button"
                            data-toggle="collapse"
                            data-target="#collapse{{ \Illuminate\Support\Str::slug($date) }}"
                            aria-expanded="false"
                            aria-controls="collapse{{ \Illuminate\Support\Str::slug($date) }}">
                        <span>
                            <i class="fa fa-calendar mr-2"></i>
                            {{ \Carbon\Carbon::parse($date)->format('M d, Y') }}
                            <span class="badge badge-primary ml-2">{{ count($entries) }}</span>
                        </span>
                        <i class="fa fa-chevron-down toggle-icon"></i>
                    </button>
                </h2>
            </div>

            <div id="collapse{{ \Illuminate\Support\Str::slug($date) }}"
                 class="collapse"
                 aria-labelledby="heading{{ \Illuminate\Support\Str::slug($date) }}"
                 data-parent="#issueHistoryAccordion">
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @foreach($entries as $entry)
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $entry->updatedBy->name }}</strong>
                                        <span class="text-muted ml-2">
                                            <small>{{ $entry->created_at->format('h:i A') }}</small>
                                        </span>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    @php
                                        $changes = json_decode($entry->changes, true);
                                    @endphp
                                    @if(isset($changes['initial_creation']))
                                        <span class="text-success">Created the issue</span>
                                    @else
                                        @foreach($changes as $field => $change)
                                            <div class="change-item">
                                                <strong>{{ ucfirst(str_replace('_', ' ', $field)) }}:</strong>
                                                <span class="text-muted">from</span>
                                                @if(in_array($field, ['target_resolution_date', 'actual_resolution_date']))
                                                    <span class="text-danger">
                                                        @if(isset($change['old']) && !empty($change['old']))
                                                            {{ \Carbon\Carbon::parse($change['old'])->format('Y-m-d') }}
                                                        @else
                                                            not set
                                                        @endif
                                                    </span>
                                                    <span class="text-muted">to</span>
                                                    <span class="text-success">
                                                        @if(isset($change['new']) && !empty($change['new']))
                                                            {{ \Carbon\Carbon::parse($change['new'])->format('Y-m-d') }}
                                                        @else
                                                            not set
                                                        @endif
                                                    </span>
                                                @else
                                                    <span class="text-danger">{{ isset($change['old']) && !empty($change['old']) ? $change['old'] : 'not set' }}</span>
                                                    <span class="text-muted">to</span>
                                                    <span class="text-success">{{ isset($change['new']) && !empty($change['new']) ? $change['new'] : 'not set' }}</span>
                                                @endif
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="alert alert-light">
            No history available for this issue.
        </div>
    @endforelse
</div>

<style>
    .change-item {
        margin-bottom: 0.25rem;
    }
    .change-item:last-child {
        margin-bottom: 0;
    }
    .toggle-icon {
        transition: transform 0.3s ease;
    }
    .btn-link.collapsed .toggle-icon {
        transform: rotate(-90deg);
    }
</style>
