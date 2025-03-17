@extends('backend.layouts.app')
@section('title','Bütün rollar')
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card table-responsive table-hover">
                        <div class="card-header">
                            <h6 class="card-title">Rol siyahısı</h6>
                            <div class="card-tools mt-3">
                                <div class="input-group input-group-md">
                                    <div class="ml-3">
                                        @can('create role')
                                            <a href="{{ route('backend.roles.create') }}">
                                                <button class="btn btn-success mr-3">
                                                    <i class="fas fa-plus-circle"></i>&nbsp; Rol əlavə et
                                                </button>
                                            </a>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- ./card-header -->
                        <span class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                <tr class="table-tr-bg">
                                    <th>Nömrə</th>
                                    <th>Rollar</th>
                                    <th class="permission-th">İcazələr</th>
                                    <th class="text-center">Butonlar</th>
                                </tr>
                                </thead>
                                <tbody id="sortable">
                                @php( $no = 1)

                                @foreach( $roles as $role)
                                    <tr id="item-{{ $role->id }}">
                                        <td class="sortable">{{ $no }}</td>
                                        <td class="sortable">
                                            <b>
                                              {{ ucfirst($role->name) }}
                                            </b>
                                        </td>
                                        <td class="sortable">
                                            @foreach( $role->permissions as $perm)
                                                <span class="badge @if( $perm->name == 'bulk delete') badge-danger
                                                                   @elseif( stripos($perm->name,"user") !== false) badge-success
                                                                   @elseif( stripos($perm->name,"role") !== false) badge-warning
                                                                   @else badge-info
                                                                   @endif
                                                    mr-2">
                                                     {{ $perm->name }}
                                                 </span>
                                            @endforeach
                                        </td>
                                        <td class="text-center">
                                             @can('view role')
                                                <a  class="btn btn-sm btn-warning text-white" id="{{ $role->id }}"
                                                   href="{{ route('backend.roles.show',$role->id) }}">
                                                <i class="fas fa-eye"></i>&nbsp;<span class="d-none d-lg-inline-block">Ətraflı</span>
                                                </a>
                                            @endcan
                                            @can('edit role')
                                                <a @if( $role->name == 'developer') style="display:none;"
                                                   @endif class="btn btn-sm  btn-primary" id="{{ $role->id }}"
                                                   href="{{ route('backend.roles.edit',$role->id) }}">
                                                <i class="fas fa-edit"></i>&nbsp;<span class="d-none d-lg-inline-block">Redaktə et</span>
                                                </a>
                                            @endcan
                                            @can('delete role')
                                                <a @if( $role->name == 'developer') style="display:none;"
                                                   @endif class="delete-item btn btn-sm btn-danger"
                                                   id="{{ $role->id }}"
                                                   href="javascript:void(0)">
                                                    <i class="fas fa-trash-alt"></i>&nbsp;
                                                    <span class="d-none d-lg-inline-block">Sil</span>
                                                </a>
                                                <form class="d-none" id="btn-delete-{{ $role->id}}"
                                                      action="{{ route('backend.roles.destroy',$role->id) }}"
                                                      method="post">
                                                    @csrf
                                                    @method('delete')
                                                </form>
                                            @endcan
                                        </td>
                                    </tr>
                                    @php( $no++ )
                                @endforeach

                                </tbody>

                            </table>
                    </div>
                    <!-- /.card-body -->
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
