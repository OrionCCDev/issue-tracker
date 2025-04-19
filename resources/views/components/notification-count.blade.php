@php
    $unreadCount = auth()->user()->unreadNotifications()->count();
@endphp

@if($unreadCount > 0)
    <span class="badge badge-danger ml-1">{{ $unreadCount }}</span>
@endif
