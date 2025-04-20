@extends('layouts.app')

@section('content')
<!--       container-fluid-fluid-fluid-fluid-fluid-fluid -->
<div class="      container-fluid-fluid-fluid-fluid-fluid-fluid mt-xl-50 mt-sm-30 mt-15">
    <div class="hk-pg-header align-items-top">
        <div>
            <h2 class="hk-pg-title font-weight-600 mb-10">Projects Management</h2>
        </div>
        @if(Auth::user()->role === 'o-admin' || Auth::user()->role === 'cm')
        <div class="d-flex">
            <a href="{{ route('projects.create') }}" class="btn btn-primary btn-sm">Add New Project</a>
        </div>
        @endif
    </div>
    <!-- Row -->
    <div class="row">
        <div class="col-xl-12">
            <section class="hk-sec-wrapper">
                <h5 class="hk-sec-title">Projects List</h5>
                <div class="row">
                    <div class="col-sm">
                        <div class="table-wrap">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Code</th>
                                            <th>Manager</th>
                                            <th>Issues</th>

                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($projects as $key => $project)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $project->name }}</td>
                                            <td>{{ $project->code }}</td>
                                            <td>
                                                @if($project->manager)
                                                <div class="media align-items-center">
                                                    <div class="media-img-wrap d-flex mr-10">
                                                        <div class="avatar avatar-xs">
                                                            <img src="{{ asset('storage/' . $project->manager->image_path) }}"
                                                                alt="user" class="avatar-img rounded-circle">
                                                        </div>
                                                    </div>
                                                    <div class="media-body">
                                                        <span class="d-block">{{ $project->manager->name }}</span>
                                                    </div>
                                                </div>
                                                @else
                                                <span class="badge badge-danger">No Manager</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('issues.index' , $project->id) }}">
                                                    <span class="badge badge-primary">{{ $project->issues_count ?? 0 }}</span>
                                                </a>
                                            </td>

                                            <td>
                                                <a href="{{ route('projects.show', $project->id) }}" class="btn btn-info btn-sm">View</a>
                                                <a href="{{ route('projects.edit', $project->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                                <a href="{{ route('projects.issues.create', $project->id) }}" class="btn btn-success btn-sm">Add Issue</a>
                                                @if(Auth::user()->role === 'o-admin' || Auth::user()->role === 'cm')
                                                <button type="button" class="btn btn-danger btn-sm"
                                                    onclick="if(confirm('Are you sure you want to delete this project?')) {
                                                        document.getElementById('delete-form-{{ $project->id }}').submit();
                                                    }">
                                                    Delete
                                                </button>
                                                <form id="delete-form-{{ $project->id }}"
                                                    action="{{ route('projects.destroy', $project->id) }}"
                                                    method="POST" style="display: none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7" class="text-center">No projects found</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end mt-20">
                            {{ $projects->links() }}
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <!-- /Row -->
</div>
<!-- /      container-fluid-fluid-fluid-fluid-fluid-fluid -->

<!-- Toast Message -->
@if(session('success'))
@section('custom_js')
<script>
    $(document).ready(function() {
        $.toast({
            heading: 'Success',
            text: '{{ session("success") }}',
            position: 'top-right',
            loaderBg: '#7a5449',
            class: 'jq-toast-primary',
            hideAfter: 3500,
            stack: 6,
            showHideTransition: 'fade'
        });
    });
</script>
@endsection
@endif
@endsection
