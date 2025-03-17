@extends('backend.layouts.app')
@section('title','İstifadəçini redaktə et')
@role('developer')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('backend.users.index') }}">Bütün istifadəçilər</a></li>
@endsection
@endrole
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <!-- left column -->
                <div class="col-md-8">
                    <!-- general form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Redaktə - <q>{{ ucfirst( $user->name)}}</q></h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        @include('partials.messages.message')
                        <form action="{{ route('backend.users.update',$user->id) }}" method="post" enctype="multipart/form-data">
                            @method('put')
                            @csrf
                            <div class="card-body">
                                <div  class="image-upload text-center">
                                    <label for="imgInp">
                                        <img id="previewImg" style="width:130px;height:130px;" class="profile-user-img img-fluid img-circle" src="{{ asset($user->avatar) }}" alt="avatar">
                                    </label>
                                    <input type="file" style="display:none" name="avatar" id="imgInp"  onchange="previewFile(this);"/>
                                    <input type="hidden" name="old_avatar" value="{{ $user->avatar }}">
                                </div>
                                <h3 class="text-muted text-center">{{ $user->name }}</h3>
                                <div class="text-center">
                                    <button type="button" class="btn
                                                      btn-@if( $user->getRoleNames()[0] == "developer")success
                                                          @elseif( $user->getRoleNames()[0] == "superadmin")info
                                                          @else btn-secondary
                                                          @endif
                                    active">{{ ucfirst($user->getRoleNames()[0] ) }}</button>
                                </div>

                                <div class="form-group">
                                    <label for="name">Ad Soyad</label>
                                    <input name="name" type="text" class="form-control" id="name"
                                           value="{{ $user->name }}">
                                </div>
                                <div class="form-group">
                                    <label for="username">İstifadəçi adı</label>
                                    <input name="username" type="text" class="form-control"  id="username"
                                           value="{{ $user->username }}">
                                </div>
                                <div class="form-group">
                                    <label for="email">Elektron poçt</label>
                                    <input name="email" type="email" class="form-control" id="email"
                                           value="{{ $user->email }}">
                                </div>
                                <div class="form-group">
                                    <label for="password">Parol</label>
                                    <input type="password" name="password" class="form-control" id="password"
                                           placeholder="Parol">
                                </div>
                                <div class="form-group">
                                    <label for="password_confirm">Parol təsdiqi</label>
                                    <input type="password" name="password_confirmation" class="form-control"
                                           id="password_confirm" placeholder="Parol təsdiqi">
                                </div>
              {{--                  @if(auth()->user()->hasRole('developer') && $user->id != auth()->user()->id )
                                    <div class="form-group">
                                        <label for="roles">Assign Role</label>
                                        <select class="form-control" name="roles[]" id="roles">
                                            <option value="" disabled selected hidden>Choose role</option>
                                            @foreach( $roles as $role)
                                                --}}{{--  @if( !auth()->user()->hasRole('developer') && $role->name =='developer' ) @continue @endif--}}{{--
                                                @if($role->name =='developer' ) @continue @endif
                                                <option value="{{ $role->name }}">{{ $role->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif--}}
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
    <script>
        function previewFile(input){
            var file = $("input[type=file]").get(0).files[0];
            if(file){
                var reader = new FileReader();

                reader.onload = function(){
                    $("#previewImg").attr("src", reader.result);
                }

                reader.readAsDataURL(file);
            }
        }
    </script>
    <script src="{{ asset('backend/assets/plugins/toastr/toastr.min.js') }}"></script>
    @if( session()->has('success') )
        <script> toastr.success('{{ session('success') }}')</script>
    @endif
    @if( session()->has('error') )
        <script> toastr.error('{{ session('error') }}')</script>
    @endif
@endpush
@push('customCss')
    <link rel="stylesheet" href="{{ asset('backend/assets/plugins/toastr/toastr.min.css') }}">
@endpush

