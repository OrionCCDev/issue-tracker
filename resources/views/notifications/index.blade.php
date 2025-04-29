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
                                                        <i class="fa fa-eye"></i> View
                                                    </a>
                                                @endif

                                                @if($notification->read_at)
                                                    <form action="{{ route('notifications.mark-as-unread', $notification->id) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="btn btn-outline-secondary btn-sm">
                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M502.3 190.8c3.9-3.1 9.7-.2 9.7 4.7V400c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V195.6c0-5 5.7-7.8 9.7-4.7 22.4 17.4 52.1 39.5 154.1 113.6 21.1 15.4 56.7 47.8 92.2 47.6 35.7 .3 72-32.8 92.3-47.6 102-74.1 131.6-96.3 154-113.7zM256 320c23.2 .4 56.6-29.2 73.4-41.4 132.7-96.3 142.8-104.7 173.4-128.7 5.8-4.5 9.2-11.5 9.2-18.9v-19c0-26.5-21.5-48-48-48H48C21.5 64 0 85.5 0 112v19c0 7.4 3.4 14.3 9.2 18.9 30.6 23.9 40.7 32.4 173.4 128.7 16.8 12.2 50.2 41.8 73.4 41.4z"/></svg>
                                                        </button>
                                                    </form>
                                                @else
                                                    <form action="{{ route('notifications.mark-as-read', $notification->id) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="btn btn-outline-success btn-sm">
                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M176 216h160c8.8 0 16-7.2 16-16v-16c0-8.8-7.2-16-16-16H176c-8.8 0-16 7.2-16 16v16c0 8.8 7.2 16 16 16zm-16 80c0 8.8 7.2 16 16 16h160c8.8 0 16-7.2 16-16v-16c0-8.8-7.2-16-16-16H176c-8.8 0-16 7.2-16 16v16zm96 121.1c-16.4 0-32.8-5.1-46.9-15.2L0 250.9V464c0 26.5 21.5 48 48 48h416c26.5 0 48-21.5 48-48V250.9L302.9 401.9c-14 10.1-30.4 15.2-46.9 15.2zm237.6-254.2c-8.9-6.9-17.2-13.5-29.6-22.8V96c0-26.5-21.5-48-48-48h-77.6c-3-2.2-5.9-4.3-9-6.6C312.6 29.2 279.2-.4 256 0c-23.2-.4-56.6 29.2-73.4 41.4-3.2 2.3-6 4.4-9 6.6H96c-26.5 0-48 21.5-48 48v44.1c-12.4 9.3-20.8 15.9-29.6 22.8A48 48 0 0 0 0 200.7v10.7l96 69.4V96h320v184.7l96-69.4v-10.7c0-14.7-6.8-28.7-18.4-37.8z"/></svg>
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
