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

    <!-- Notifications List -->
    <section class="hk-sec-wrapper">
        <div class="row">
            <div class="col-sm">
                <div class="card-group">
                    @forelse($notifications as $notification)
                        <div class="card mb-3 {{ $notification->read_at ? 'bg-light' : 'border-left-info' }}"
                             style="{{ $notification->read_at ? '' : 'border-left: 4px solid #2c95ff;' }}">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h5 class="card-title mb-0">
                                        {{ ucfirst(str_replace('_', ' ', $notification->type)) }}
                                    </h5>
                                    <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                </div>

                                <p class="card-text">
                                    @if(isset($notification->data['message']))
                                        {{ $notification->data['message'] }}
                                    @else
                                        {{ json_encode($notification->data) }}
                                    @endif
                                </p>

                                @if(isset($notification->data['url']))
                                    <a href="{{ $notification->data['url'] }}" class="btn btn-outline-primary btn-sm">
                                        View Details
                                    </a>
                                @endif

                                <div class="d-flex mt-3">
                                    @if($notification->read_at)
                                        <form action="{{ route('notifications.mark-as-unread', $notification->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-secondary btn-sm">
                                                Mark as Unread
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('notifications.mark-as-read', $notification->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-success btn-sm">
                                                Mark as Read
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center">
                            <p>No notifications found.</p>
                        </div>
                    @endforelse
                </div>

                <div class="d-flex justify-content-end mt-20">
                    {{ $notifications->links() }}
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
