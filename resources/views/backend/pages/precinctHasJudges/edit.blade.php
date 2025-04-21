@extends('backend.layouts.app')
@section('title','Münsifə aid nominasiyalar')
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
                        <form action="{{ route('backend.precincts-has-judges.update',$data->id) }}" method="post" enctype="multipart/form-data">
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
                                    <label>Münsiflər</label>
                                    <select name="judge_id" class="form-control select2bs4 required" placeholder="Münsifi seçin">
                                        @foreach( $judges as $j)
                                            <option value="{{ $j->id }}" @if($j->id == $data->judge_id ) selected @endif>{{ ucfirst($j->name )}}</option>
                                        @endforeach
                                    </select>
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

