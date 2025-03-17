@extends('backend.layouts.app')
@section('title','Create Role')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('backend.roles.index') }}">All Roles</a></li>
@endsection
@section('content')

    <section class="content">

        <div class="container-fluid">

            <div class="row justify-content-center">

                <!-- left column -->
                <div class="col-md-8">
                @include('partials.messages.message')
                <!-- general form elements -->
                    <div class="card card-dark">
                        <div class="card-header">
                            <h3 class="card-title">Create role</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->

                        <form action="{{ route('backend.roles.store') }}" method="post">
                            @csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input name="name" type="text" class="form-control" id="name"
                                           placeholder="Name...">
                                </div>
                                <div class="form-group">
                                    <h2>Permissions</h2>
                                    <div class="container mb-3">
                                        <div class="row pb-2">
                                            <div class="col-1">
                                                <div style="width: 26px;height: 100% ; background: #dc3545;"></div>
                                            </div>
                                            <div class="col-8"><b><q><i>Developer</i></q> and <q><i>Admin</i></q> Permissions</b>
                                            </div>
                                        </div>
                                        <div class="row pb-2">
                                            <div class="col-1">
                                                <div style="width: 26px;height: 100% ; background: #17a2b8;"></div>
                                            </div>
                                                <div class="col-8"><b><q><i>Developer</i></q> Permissions</b></div>
                                        </div>
                                        <div class="row pb-2">
                                            <div class="col-1">
                                                <div style="width: 26px;height: 100% ; background: #28a745;"></div>
                                            </div>
                                            <div class="col-8"><b><q><i>All Users</i></q> Permissions</div></b>
                                        </div>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" value="1" class="form-check-input"
                                               id="checkPermissionAll">
                                        <label class="form-check-label" for="checkPermissionAll"><b>Select All / Deselect
                                                All</b></label>
                                    </div>
                                    <hr>
                                    @php $i = 1; @endphp
                                    @foreach( $permission_groups as $group )
                                        <div class="row">
                                            <div
                                                class="col-12 pt-1 pb-1 @if( $group->name == "user" ||  $group->name == "bulk delete") bg-danger
                                                               @elseif( $group->name == "role") bg-info
                                                               @else bg-success
                                                               @endif
                                                    ">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input"
                                                           id="Management{{ $i }}" value="{{ $group->name }}"
                                                           onclick="checkPermissionByGroup('role-{{ $i }}-management-checkbox',this)">
                                                    <label class="form-check-label role-label-name"
                                                           for="Management{{ $i }}">{{ $group->name }}</label>
                                                </div>
                                            </div>
                                            <div class="col-12 role-{{ $i }}-management-checkbox mt-3 mb-3">
                                                @php
                                                    $permissions = App\Models\User::getPermissionByGroupName($group->name);
                                                     $j = 1;
                                                @endphp
                                                @foreach( $permissions as $permission )
                                                    <div class="col-3 form-check mr-5" style="display:inline-block;">
                                                        <input type="checkbox" name="permissions[]"
                                                               value="{{ $permission->name }}" class="form-check-input"
                                                               id="checkPermission{{ $permission->id }}">
                                                        <label class="form-check-label
                                                        @if(  $permission->name == "create users" ||  $permission->name == "edit users" || $permission->name == "delete users" || $permission->name == "bulk delete"||  $permission->name == "view users"  )
                                                            text-danger font-weight-bold
                                                            @elseif( $permission->name == "create role" || $permission->name == "edit role" ||  $permission->name == "delete role" ||  $permission->name == "view role")
                                                            text-info font-weight-bold
                                                            @else
                                                            text-success font-weight-bold
                                                                   @endif"
                                                               for="checkPermission{{ $permission->id }}">
                                                            {{ $permission->name }}
                                                        </label>
                                                    </div>
                                                    @php $j++; @endphp
                                                @endforeach
                                                <br>
                                            </div>
                                        </div>
                                        @php $i++; @endphp
                                    @endforeach
                                </div>
                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer">
                                <button type="submit" class="btn btn-dark">Save</button>
                            </div>
                        </form>
                    </div>
                    <!-- /.card -->


                </div>
                <!--/.col (left) -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
@endsection
@push('customJs')
    @include('partials.scripts.checkbox')
@endpush

