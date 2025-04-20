@extends('layouts.app')

@section('content')
<div class="container mt-xl-50 mt-sm-30 mt-15">
    <div class="hk-pg-header align-items-top">
        <div>
            <h2 class="hk-pg-title font-weight-600 mb-10">Notifications</h2>
        </div>
        <div class="d-flex">
            <form action="{{ route('notifications.mark-all-as-read') }}" method="POST" class="mr-10">
                @csrf
                <button type="submit" class="btn btn-primary btn-sm">
                    Mark All as Read
                </button>
            </form>
            <a href="{{ route('notifications.create-samples') }}" class="btn btn-info btn-sm">
                Create Sample Notifications
            </a>
        </div>
    </div>

    <!-- Notifications Table -->
    <section class="hk-sec-wrapper">
        <div class="row">
            <div class="col-sm">
                <div class="table-wrap">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered mb-0">
                            <thead class="thead-primary">
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="20%">Type</th>
                                    <th width="35%">Message</th>
                                    <th width="15%">Time</th>
                                    <th width="10%">Status</th>
                                    <th width="15%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($notifications as $key => $notification)
                                    <tr class="{{ $notification->read_at ? 'bg-light' : 'bg-light-info' }}">
                                        <td>{{ $notifications->firstItem() + $key }}</td>
                                        <td>
                                            <span class="font-weight-600">
                                                {{ ucfirst(str_replace('_', ' ', $notification->type)) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if(isset($notification->data['message']))
                                                {{ $notification->data['message'] }}
                                            @else
                                                {{ json_encode($notification->data) }}
                                            @endif
                                        </td>
                                        <td>
                                            <span class="text-muted">{{ $notification->created_at->diffForHumans() }}</span>
                                            <br>
                                            <small class="text-muted">{{ $notification->created_at->format('M d, Y g:i A') }}</small>
                                        </td>
                                        <td>
                                            @if($notification->read_at)
                                                <span class="badge badge-light">Read</span>
                                            @else
                                                <span class="badge badge-info">Unread</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex">
                                                @if(isset($notification->data['url']))
                                                    <a href="{{ $notification->data['url'] }}" class="btn btn-primary btn-sm mr-10">
                                                        <i class="fas fa-eye"></i> View
                                                    </a>
                                                @endif

                                                @if($notification->read_at)
                                                    <form action="{{ route('notifications.mark-as-unread', $notification->id) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="btn btn-outline-secondary btn-sm">
                                                            <i class="fas fa-envelope"></i>
                                                        </button>
                                                    </form>
                                                @else
                                                    <form action="{{ route('notifications.mark-as-read', $notification->id) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="btn btn-outline-success btn-sm">
                                                            <i class="fas fa-envelope-open"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No notifications found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end mt-20">
                        {{ $notifications->links() }}
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
    .thead-primary th {
        background-color: #0f5874 !important;
        color: white;
    }
    .bg-light-info {
        background-color: #e8f4ff;
    }
    .mr-10 {
        margin-right: 10px;
    }
</style>
@endsection
