@extends('backend.layouts.app')
@section('title','Edit Role')
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
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Edit role - <q>{{ ucfirst( $role->name)}}</q></h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->

                        <form action="{{ route('backend.roles.update',$role->id) }}" method="post">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="name">Role name</label>
                                    <input name="name" value="{{ $role->name }}" type="text" class="form-control"
                                           id="name"
                                           placeholder="Role name...">
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
                                        <input type="checkbox" value="1"
                                               {{ App\Models\User::roleHasPermissions($role,$permissions) ? 'checked': ''}} class="form-check-input"
                                               id="checkPermissionAll">
                                        <label class="form-check-label" for="checkPermissionAll"><b>Select All /
                                                Deselect All</b></label>
                                    </div>
                                    <hr>
                                    @php $i = 1; @endphp
                                    @foreach( $permission_groups as $group )
                                        <div class="row">
                                            @php
                                                $permissions = App\Models\User::getPermissionByGroupName($group->name);
                                            /*    $roleHasPermission = App\Models\User::checkRoleHasPermission();*/
                                                 $j = 1;
                                            @endphp
                                            <div
                                                class="col-12 pt-1 pb-1  @if( $group->name == "user" ||  $group->name == "bulk delete") bg-danger
                                                               @elseif( $group->name == "role") bg-info
                                                               @else bg-success
                                                               @endif">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input"
                                                           id="Management{{ $i }}" value="{{ $group->name }}"
                                                           onclick="checkPermissionByGroup('role-{{ $i }}-management-checkbox',this)"
                                                        {{ App\Models\User::roleHasPermissions($role,$permissions) ? 'checked': ''}}>
                                                    <label class="form-check-label role-label-name"
                                                           for="Management{{ $i }}">{{ $group->name }}</label>
                                                </div>
                                            </div>

                                            <div class="col-12 mt-3 mb-3 role-{{ $i }}-management-checkbox">
                                                @foreach( $permissions as $permission )
                                                    <div class="col-3 form-check mr-5" style="display:inline-block;">
                                                        <input type="checkbox"
                                                               name="permissions[]"
                                                               {{ $role->hasPermissionTo($permission->name) ? 'checked' : ''  }}
                                                               value="{{ $permission->name }}" class="form-check-input"
                                                               id="checkPermission{{ $permission->id }}">
                                                        <label class="form-check-label
                                                    @if( $permission->name == "create users" ||  $permission->name == "edit users" || $permission->name == "delete users" || $permission->name == "view users" || $permission->name == "bulk delete") font-weight-bold text-danger
                                                   @elseif( $permission->name == "create role" || $permission->name == "edit role" ||  $permission->name == "delete role" || $permission->name == "view role" ) font-weight-bold text-info
                                                   @else
                                                            font-weight-bold text-success
                                                       @endif"
                                                               for="checkPermission{{ $permission->id }}">{{ $permission->name }}</label>
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
                                <button type="submit" class="btn btn-primary">Yenilə</button>
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


