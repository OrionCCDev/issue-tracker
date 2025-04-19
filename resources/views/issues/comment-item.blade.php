<div class="media mb-3 comment-item" data-comment-id="{{ $comment->id }}">
    <div class="media-img-wrap mr-3">
        <div class="avatar avatar-sm">
            <img src="{{ asset('storage/' . $comment->user->image_path) }}"
                alt="user" class="avatar-img rounded-circle">
        </div>
    </div>
    <div class="media-body">
        <div class="card">
            <div class="card-header bg-transparent py-2">
                <div class="d-flex justify-content-between">
                    <div>
                        <strong>{{ $comment->user->name }}</strong>
                        <span class="text-muted ml-2">
                            <small>{{ $comment->created_at->diffForHumans() }}</small>
                        </span>
                    </div>
                    @if(Auth::id() == $comment->user_id)
                        <div class="dropdown">
                            <a href="#" class="btn btn-sm btn-icon btn-light" data-toggle="dropdown">
                                <i class="fa fa-ellipsis-v"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item edit-comment" href="#" data-comment-id="{{ $comment->id }}">
                                    Edit
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item text-danger delete-comment" href="#" data-comment-id="{{ $comment->id }}">
                                    Delete
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <div class="card-body py-2 comment-content">
                {!! nl2br(e($comment->description)) !!}
            </div>
        </div>
    </div>
</div>
