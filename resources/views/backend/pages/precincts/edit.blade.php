@extends('backend.layouts.app')
@section('title','Məntəqə')
@section('content')
    <section class="content">

        <div class="container-fluid">

            <div class="row justify-content-center">

                <!-- left column -->
                <div class="col-md-6">
                {{-- @include('partials.messages.message')--}}
                <!-- general form elements -->
                    <div class="card card-dark">
                        <div class="card-header">
                            <h3 class="card-title">Yenilə</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->

                        <form action="{{ route('backend.precincts.update',$precinct->id) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Şəhərlər/Rayonlar</label>
                                    <select name="city_id" class="form-control select2bs4 required" placeholder="Şəhər/Rayon seçin">
                                        @foreach( $data as $p)
                                            <option value="{{ $p->id }}" @if($p->id == $precinct->city_id ) selected @endif>{{ ucfirst($p->city_name )}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="place_name">Məntəqənin adı</label>
                                    <input id="place_name" name="place_name" type="text"
                                           class="form-control" value="{{ $precinct->place_name }}">
                                </div>
                                <div class="form-group">
                                    <label for="place_address">Ünvan</label>
                                    <input id="place_address" name="place_address" type="text" value="{{ $precinct->place_address }}"
                                           class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="first_person">Birinci cavabdeh şəxs</label>
                                    <input id="first_person" name="first_person" type="text" value="{{ $precinct->first_person }}"
                                           class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="first_person_number">Birinci cavabdeh şəxsin nömrəsi</label>
                                    <input id="first_person_number" name="first_person_number" type="text" value="{{ $precinct->first_person_number }}"
                                           class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="second_person">İkinci cavabdeh şəxs</label>
                                    <input id="second_person" name="second_person" type="text" value="{{ $precinct->second_person }}"
                                           class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="second_person_number">İkinci cavabdeh şəxsin nömrəsi</label>
                                    <input id="second_person_number" name="second_person_number" type="text" value="{{ $precinct->second_person_number }}"
                                           class="form-control">
                                </div>

                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer">
                                <button type="submit" class="btn btn-dark">Yenilə</button>
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
@endpush

