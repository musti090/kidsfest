@extends('backend.layouts.app')
@section('title','Role View')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('backend.roles.index') }}">All Roles</a></li>
@endsection
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="m-3">
                        @can('edit role')
                            <a @if( $role->name == 'developer') style="display:none;"
                               @endif class="btn btn-sm  btn-primary" id="{{ $role->id }}"
                               href="{{ route('backend.roles.edit',$role->id) }}">
                                <i class="fas fa-edit"></i>&nbsp;<span class="d-none d-lg-inline-block">Edit</span>
                            </a>
                        @endcan
                        @can('delete role')
                            <a @if( $role->name == 'developer') style="display:none;"
                               @endif class="delete-item btn btn-sm btn-danger"
                               id="{{ $role->id }}"
                               href="javascript:void(0)">
                                <i class="fas fa-trash-alt"></i>&nbsp;
                                <span class="d-none d-lg-inline-block">Delete</span>
                            </a>
                            <form class="d-none" id="btn-delete-{{ $role->id}}"
                                  action="{{ route('backend.roles.destroy',$role->id) }}"
                                  method="post">
                                @csrf
                                @method('delete')
                            </form>
                        @endcan
                        @can('view role')
                            <a class="btn btn-sm btn-warning text-white" href="{{ route('backend.roles.index') }}">
                                <i class="fas fa-list"></i>&nbsp;<span
                                    class="d-none d-lg-inline-block">Return to List</span>
                            </a>
                        @endcan
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <h3>Role name</h3>
                            <button class="btn
                                                      btn-@if( $role->name == "developer")success
                                                          @elseif( $role->name == "admin")info
                                                          @elseif( $role->name == "editor")secondary
                                                          @endif
                                active">{{ ucfirst($role->name) }}</button>

                        </li>
                        <li class="list-group-item">
                            <h3>Role permissions</h3>
                            <p>
                                @foreach( $role->permissions()->get() as $key => $pername)
                                    <button class="btn  @if( $pername->name == 'bulk delete') btn-danger
                                                                   @elseif( $pername->name == 'view reports') btn-primary
                                                                   @elseif( stripos($pername->name,"user") !== false) btn-success
                                                                   @elseif( stripos($pername->name,"role") !== false) btn-warning
                                                                   @else badge-info
                                                                   @endif
                                        active mt-2"><b>{{ $pername->name }}</b></button>
                                @endforeach
                            </p>
                        </li>
                        <li class="list-group-item">
                            <h3>Role users</h3>
                            @foreach( $role->users  as $key=>$user)
                                <a href="{{ route('backend.users.show',$user->id) }}" class="btn  btn-info  mt-2 mr-2">
                                    <span class="badge badge-pill badge-warning"> {{ $key + 1}}</span>
                                    <b>{{ $user->name }}</b></a>
                            @endforeach
                        </li>

                    </ul>
                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
@endsection
@push('customJs')
    <script src="{{ asset('backend/assets/plugins/toastr/toastr.min.js') }}"></script>
    @if( session()->has('success') )
        <script> toastr.success('{{ session('success') }}')</script>
    @endif
    @if( session()->has('error') )
        <script> toastr.success('{{ session('error') }}')</script>
    @endif
    <script>
        $(".delete-item").on('click', function (e) {
            let destroy_id = e.currentTarget.id;
            alertify.confirm(' Are you sure you want to delete ?', null,
                function () {
                    event.preventDefault();
                    document.getElementById('btn-delete-' + destroy_id).submit();
                },
                function () {
                    alertify.error('Cancel')
                }
            )
        });
    </script>
@endpush
@push('customCss')
    <link rel="stylesheet" href="{{ asset('backend/assets/plugins/toastr/toastr.min.css') }}">
    <!-- CSS -->
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css"/>
    <!-- Default theme -->
    <link rel="stylesheet" href="{{ asset('backend/assets/myCustom/css/alertify/default.min.css') }}"/>
    <!-- Semantic UI theme -->
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/semantic.min.css"/>
    <!-- Bootstrap theme -->
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/bootstrap.min.css"/>
    <!-- JavaScript -->
    <script src="{{ asset('backend/assets/myCustom/js/alertify/alertify.min.js') }}"></script>
@endpush


