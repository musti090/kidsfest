@extends('backend.layouts.app')
@section('title','Bütün istifadəçilər')
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card table-responsive table-hover">
                        <div class="card-header">
                            <h6 class="card-title">İstifadəçilərin siyahısı</h6>
                            <div class="card-tools mt-3">
                                <div class="input-group input-group-md">
                                    <div>
                                        @role('developer')
                                        <a href="{{ route('backend.users.create') }}">
                                            <button class="btn btn-success mr-3">
                                                <i class="fas fa-plus-circle"></i>&nbsp; İstifadəçi əlavə et
                                            </button>
                                        </a>
                                        @endrole
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- ./card-header -->
                        <div class="card-body">
                            <table class="table text-center">
                                <thead>
                                <tr class="table-tr-bg">
                                    <th>Nömrə</th>
                                    <th>İstifadəçinin şəkli</th>
                                    <th>Ad Soyad</th>
                                    <th>İstifadəçi adı</th>
                                    <th>Elektron poçt</th>
                                    <th>Rol</th>
                                    <th class="text-center">Butonlar</th>
                                </tr>
                                </thead>
                                <tbody style="padding-top: 10px" id="sortable">
                                @php
                                    $no = 1
                                @endphp
                                @foreach( $users as $user)
                                    @if( $user->hasRole('developer') && $user->id != auth()->user()->id) @continue @endif
                                    <tr id="item-{{ $user->id }}">
                                        <td class="sortable">{{ $no }}</td>
                                        <td>
                                            @if($user->avatar != null)
                                            <img class="img-circle" style="width: 80px;height: 80px"
                                                 src="{{ asset($user->avatar) }}" alt="{{ $user->name }}">
                                            @else
                                                <span>Şəkil yüklənməyib</span>
                                                @endif
                                        </td>
                                        <td class="sortable">{{ $user->name }}</td>
                                        @if(!empty($user->username))
                                            <td class="sortable text-info font-weight-bolder">{{ $user->username }}</td>
                                        @else
                                            <td class="sortable text-danger">Boş</td>
                                        @endif
                                        <td class="sortable">{{ $user->email }}</td>
                                        <td class="sortable">
                                            @foreach( $user->roles as $role)
                                                <span class="badge badge-pill
                                                   badge-@if( $role->name == 'developer')success
                                                          @elseif( $role->name == 'superadmin')info
                                                          @elseif( $role->name == 'admin' || $role->name == 'content manager')secondary
                                                          @endif
                                                    text-uppercase">
                                                        {{ $role->name }}
                                                    </span>
                                            @endforeach
                                        </td>
                                        <td class="text-center">
                                            @role('developer')
                                            <a class="btn btn-sm btn-warning text-white" id="{{ $user->id }}"
                                               href="{{ route('backend.users.show',$user->id) }}">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @endrole
                                            @role('developer|superadmin')
                                            <a class="btn btn-sm  btn-primary" id="{{ $user->id }}"
                                               href="{{ route('backend.users.edit',$user->id) }}">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @endrole
                                                @can('delete users')
                                                    <a class="delete-item btn btn-sm btn-danger" id="{{ $user->id }}"
                                                       href="javascript:void(0)">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </a>
                                                    <form class="d-none" id="btn-delete-{{ $user->id}}"
                                                          action="{{ route('backend.users.destroy',$user->id) }}"
                                                          method="post">
                                                        @csrf
                                                        @method('delete')
                                                    </form>
                                                @endcan
                                        </td>
                                    </tr>
                                    @php
                                        $no++
                                    @endphp
                                @endforeach
                                </tbody>

                            </table>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer clearfix">
                            {{ $users->links('vendor.pagination.custom') }}
                        </div>
                    </div>
                    <!-- /.card -->

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
        <script> toastr.error('{{ session('error') }}')</script>
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


