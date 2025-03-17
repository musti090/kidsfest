@extends('backend.layouts.app')
@section('title','İstifadəçi Məlumatları')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('backend.users.index') }}">Bütün istifadəçilər</a></li>
@endsection
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="m-3">
                        @role('developer')
                        <a class="btn btn-sm  btn-primary" id="{{ $user->id }}"
                           href="{{ route('backend.users.edit',$user->id) }}">
                            <i class="fas fa-edit"></i>&nbsp;<span class="d-none d-lg-inline-block">&nbsp;Redaktə et</span>
                        </a>
                        @endrole
                        @can('delete users')
                            <a class="delete-item btn btn-sm btn-danger" id="{{ $user->id }}"
                               href="javascript:void(0)">
                                <i class="fas fa-trash-alt"></i>&nbsp;
                                <span class="d-none d-lg-inline-block">Sil</span>
                            </a>
                            <form class="d-none" id="btn-delete-{{ $user->id}}"
                                  action="{{ route('backend.users.destroy',$user->id) }}" method="post">
                                @csrf
                                @method('delete')
                            </form>
                        @endcan

                        <a class="btn btn-sm btn-warning text-white" href="{{ route('backend.users.index') }}">
                            <i class="fas fa-list"></i>&nbsp;<span
                                    class="d-none d-lg-inline-block">&nbsp;Siyahıya qayıt</span>
                        </a>
                    </div>
                    <ul class="list-group list-group-flush">
                        @if($user->avatar != null)
                        <li class="list-group-item">
                            <h3>İstifadəçinin profil şəkli</h3>
                            <p><img style="max-height: 300px" class="img-thumbnail" src="{{ asset($user->avatar)}}"
                                    alt="İstifadəçinin profil şəkli"></p>
                        </li>
                        @endif

                        <li class="list-group-item">
                            <h3>Ad Soyad</h3>
                            <p class="text-info" style="font-size: 25px">{{ $user->name }}</p>
                        </li>
                        <li class="list-group-item">
                            <h3>İstifadəçi adı</h3>
                            @if(!empty($user->username))
                                <p class="text-info" style="font-size: 25px">{{ $user->username }}</p>
                            @else
                                <p class="text-danger" style="font-size: 25px">Boş</p>
                            @endif

                        </li>
                        <li class="list-group-item">
                            <h3>Elektron poçt</h3>
                            <p class="text-info" style="font-size: 25px">{{ $user->email }}</p>
                        </li>
                        <li class="list-group-item">
                            <h3>Yaradılma tarixi</h3>
                            <p class="text-info" style="font-size: 25px">
                                {{ \Carbon\Carbon::parse($user->created_at )->format('d-m-Y H:i:s')  }}
                            </p>
                        </li>
                        <li class="list-group-item">
                            <h3>Rol</h3>
                            <p>
                                @forelse ( $user->roles as $role)
                                    <a href="@role('developer') {{ route('backend.roles.show',$role->id) }} @endrole" class="btn
                                                      btn-@if( $role->name == "developer")success
                                                          @elseif( $role->name == "superadmin")info
                                                          @elseif( $role->name == "admin" || $role->name == "content manager")secondary
                                                          @endif
                                            ">
                                        {{ ucfirst($role->name)  }}
                                        @empty
                                            Not assigned
                                        @endforelse
                                    </a>
                            </p>
                        </li>
                        {{--   <li class="list-group-item">
                               <h3>İcazələr</h3>
                               <p>
                                   @forelse ( $user->roles as $role)
                                       @foreach( $role->permissions()->get() as $key => $pername)
                                           <button class="btn  @if( $pername->name == 'bulk delete') btn-danger
                                                                      @elseif( $pername->name == 'view reports') btn-primary
                                                                      @elseif( stripos($pername->name,"user") !== false) btn-success
                                                                      @elseif( stripos($pername->name,"role") !== false) btn-warning
                                                                      @else badge-info
                                                                      @endif
                                               active mt-2"><b>{{ $pername->name }}</b></button>
                                       @endforeach
                                   @empty
                                       Not assigned
                                   @endforelse
                               </p>
                           </li>--}}
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
                    //location.href = "/my-admin/users/delete/" + destroy_id;
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

