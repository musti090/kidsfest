@extends('backend.layouts.app')
@section('title','Məntəqəyə aid nominasiyalar')
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

                        <form action="{{ route('backend.precincts-has-nominations.update',$data->id) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Məntəqələr</label>
                                    <select name="precinct_id" class="form-control select2bs4 required" placeholder="Məntəqəni seçin">
                                        @foreach( $precincts as $p)
                                            <option value="{{ $p->id }}"  @if($p->id == $data->precinct_id ) selected @endif>{{ ucfirst($p->place_name )}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Nominasiyalar</label>
                                    <select name="nomination_id" class="form-control select2bs4 required" placeholder="Nominasiyanı seçin">
                                        @foreach( $nominations as $n)
                                            <option value="{{ $n->id }}" @if($n->id == $data->nomination_id ) selected @endif>{{ ucfirst($n->name )}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="start_date">Başlama tarixi</label>
                                    <input id="start_date" name="start_date" type="date" value="{{ $data->start_date }}"
                                           class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="end_date">Bitmə tarixi</label>
                                    <input id="end_date" name="end_date" type="date" value="{{ $data->end_date }}"
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

