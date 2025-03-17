@extends('backend.layouts.app')
@section('title','İstifadəçi yarat')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('backend.users.index') }}">Bütün istifadəçilər</a></li>
@endsection
@section('content')
    <section class="content">

        <div class="container-fluid">

            <div class="row justify-content-center">

                <!-- left column -->
                <div class="col-md-8">
                {{-- @include('partials.messages.message')--}}
                <!-- general form elements -->
                    <div class="card card-dark">
                        <div class="card-header">
                            <h3 class="card-title">İstifadəçi yarat</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->

                        <form action="{{ route('backend.users.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="image-upload text-center">
                                    <label for="imgInp">
                                        <img id="profileImage" style="width:130px;height:130px;"
                                             class="profile-user-img img-fluid img-circle"
                                             src="{{ asset('backend/assets/img/images.jfif') }}" alt="avatar">
                                    </label>
                                    <input type="file" style="display:none" name="avatar" id="imgInp"/>
                                </div>

                                <div class="form-group">
                                    <label for="name">Ad Soyad</label>
                                    <input name="name" type="text" class="form-control" value="{{ old('name') }}"
                                           id="name"
                                           placeholder="Ad Soyad...">
                                    @error('name')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="username">İstifadəçi adı</label>
                                    <input name="username" type="text" class="form-control"
                                           value="{{ old('username') }}" id="username"
                                           placeholder="İstifadəçi adı...">
                                    @error('username')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="email">İstifadəçinin elektron poçtu</label>
                                    <input name="email" type="text" class="form-control" id="email"
                                           value="{{ old('email')}}"
                                           placeholder="İstifadəçinin elektron poçtu...">
                                    @error('email')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="password">İstifadəçinin parolu</label>
                                    <input name="password" type="password" class="form-control" id="password"
                                           placeholder="İstifadəçinin parolu...">
                                    @error('password')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="password_confirm">Parolun təsdiqi</label>
                                    <input name="password_confirmation" type="password" class="form-control"
                                           id="password_confirm"
                                           placeholder="Parolun təsdiqi...">
                                    @error('password_confirmation')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                @if(auth()->user()->hasRole('developer') )
                                    <div class="form-group">
                                        <label for="roles">Assign Role</label>
                                        <select class="form-control" name="roles[]" id="roles">
                                            <option value="" disabled selected hidden>Choose role</option>
                                            @foreach( $roles as $role)
                                                {{--  @if( !auth()->user()->hasRole('developer') && $role->name =='developer' ) @continue @endif--}}
                                                @if($role->name =='developer' ) @continue @endif
                                                <option value="{{ $role->name }}">{{ $role->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @else
                                    <input type="hidden" name="roles[]" value="superadmin">
                                @endif

                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer">
                                <button type="submit" class="btn btn-dark">Yadda saxla</button>
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
@push('customCss')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('backend/assets/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <style>
        .hide-precincts{
            display: none;
        }
    </style>
@endpush
@push('customJs')
    <script>
        let imgInp = document.getElementById('imgInp');
        let profileImage = document.getElementById('profileImage');
        document.getElementById('imgInp').addEventListener('change', function (e) {
            const [file] = e.target.files;
            console.log(URL.createObjectURL(file));
            if (file) {
                profileImage.setAttribute('src', URL.createObjectURL(file));
            }
        })
    </script>
    <!-- Select2 -->
    <script src="{{ asset('backend/assets/plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        $(function () {
            //Initialize Select2 Elements
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            })
        })
    </script>
    <script>
        $(document).ready(function () {
            $('#sub_category_name').on('change', function () {
                $('.hide-precincts').css("display", "block");
                let id = $(this).val();
                $('#sub_category').empty();
                $('#sub_category').append(`<option value="0" disabled selected>Yüklənir...</option>`);
                $.ajax({
                    type: 'GET',
                    url: 'precincts/' + id,
                    success: function (response) {
                        var response = JSON.parse(response);
                        console.log(response);
                        $('#sub_category').empty();
                        $('#sub_category').append(`<option value="0" disabled selected>Məntəqəni seçin</option>`);
                        response.forEach(element => {
                            $('#sub_category').append(`<option value="${element['id']}">${element['place_name']}</option>`);
                        });
                    }
                });
            });
        });
    </script>
@endpush

