@forelse($issue->comments as $comment)
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
@empty
    <div class="alert alert-light">
        No comments yet. Be the first to comment!
    </div>
@endforelse

<!-- Add Comment Form -->
<div class="card mt-3">
    <div class="card-body">
        <form id="addCommentForm" action="{{ route('projects.issues.comments.store', [$issue->project_id, $issue->id]) }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="content">Add Your Comment</label>
                <textarea class="form-control" id="content" name="description" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">
                <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                Post Comment
            </button>
        </form>
    </div>
</div>

<!-- Edit Comment Modal -->
<div class="modal fade" id="editCommentModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Comment</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editCommentForm">
                    @csrf
                    <input type="hidden" name="comment_id" id="editCommentId">
                    <div class="form-group">
                        <textarea class="form-control" id="editCommentContent" name="description" rows="3" required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveEditComment">
                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                    Save Changes
                </button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Handle new comment submission
    $('#addCommentForm').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        const button = form.find('button[type="submit"]');
        const spinner = button.find('.spinner-border');
        const textarea = form.find('textarea');
        const commentContent = textarea.val().trim();

        if (!commentContent) return;

        button.prop('disabled', true);
        spinner.removeClass('d-none');

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            success: function(response) {
                // Clear the form
                textarea.val('');

                // Remove "No comments" message if it exists
                if ($('.alert.alert-light').length) {
                    $('.alert.alert-light').remove();
                }

                // Add the new comment to the list
                $('.comment-item:first').before(response);

                // Show success message
                toastr.success('Comment added successfully');
            },
            error: function(xhr) {
                toastr.error('Failed to add comment. Please try again.');
            },
            complete: function() {
                button.prop('disabled', false);
                spinner.addClass('d-none');
            }
        });
    });

    // Handle edit comment
    $('.edit-comment').on('click', function(e) {
        e.preventDefault();
        const commentId = $(this).data('comment-id');
        const commentContent = $(`.comment-item[data-comment-id="${commentId}"] .comment-content`).text().trim();

        $('#editCommentId').val(commentId);
        $('#editCommentContent').val(commentContent);
        $('#editCommentModal').modal('show');
    });

    // Handle save edited comment
    $('#saveEditComment').on('click', function() {
        const button = $(this);
        const spinner = button.find('.spinner-border');
        const commentId = $('#editCommentId').val();
        const content = $('#editCommentContent').val();

        if (!content.trim()) return;

        button.prop('disabled', true);
        spinner.removeClass('d-none');

        $.ajax({
            url: `/projects/{{ $issue->project_id }}/issues/{{ $issue->id }}/comments/${commentId}/update`,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                description: content
            },
            success: function(response) {
                $(`.comment-item[data-comment-id="${commentId}"] .comment-content`).html(response.content.replace(/\n/g, '<br>'));
                $('#editCommentModal').modal('hide');
                toastr.success('Comment updated successfully');
            },
            error: function(xhr) {
                toastr.error('Failed to update comment. Please try again.');
            },
            complete: function() {
                button.prop('disabled', false);
                spinner.addClass('d-none');
            }
        });
    });

    // Handle delete comment
    $('.delete-comment').on('click', function(e) {
        e.preventDefault();
        const commentId = $(this).data('comment-id');

        if (confirm('Are you sure you want to delete this comment?')) {
            $.ajax({
                url: `/projects/{{ $issue->project_id }}/issues/{{ $issue->id }}/comments/${commentId}/delete`,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    _method: 'DELETE'
                },
                success: function() {
                    $(`.comment-item[data-comment-id="${commentId}"]`).remove();
                    toastr.success('Comment deleted successfully');

                    // Show empty message if no comments left
                    if ($('.comment-item').length === 0) {
                        $('.card.mt-3').before(`
                            <div class="alert alert-light">
                                No comments yet. Be the first to comment!
                            </div>
                        `);
                    }
                },
                error: function() {
                    toastr.error('Failed to delete comment. Please try again.');
                }
            });
        }
    });
});
</script>
