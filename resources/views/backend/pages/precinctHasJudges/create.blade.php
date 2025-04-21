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
                            <h3 class="card-title">Əlavə et</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->

                        <form action="{{ route('backend.precincts-has-judges.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">

                                <div class="form-group">
                                    <label>Məntəqələr</label>
                                    <select name="precinct_id" class="form-control select2bs4 required">
                                        <option value="0" disabled selected hidden>Məntəqəni seçin</option>
                                        @foreach( $precincts as $p)
                                            <option value="{{ $p->id }}">{{ ucfirst($p->place_name )}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Nominasiyalar</label>
                                    <select name="nomination_id" class="form-control select2bs4 required">
                                        <option value="0" disabled selected hidden>Nominasiyanı seçin</option>
                                        @foreach( $nominations as $n)
                                            <option value="{{ $n->id }}">{{ ucfirst($n->name )}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Münsiflər</label>
                                    <select name="judge_id[]" class="form-control select2bs4 required" data-placeholder="Münsifi seçin" multiple >
                                        @foreach( $judges as $j)
                                            <option value="{{ $j->id }}">{{ ucfirst($j->name )}}</option>
                                        @endforeach
                                    </select>
                                </div>

                            {{--        <div class="form-group">
                                        <label>Münsiflər</label>
                                        <select class="select2" multiple="multiple" data-placeholder="Münsifləri seçin"
                                                style="width: 100%;">
                                            <option value="0" disabled selected hidden>Münsifləri seçin</option>
                                            @foreach( $judges as $j)
                                                <option value="{{ $j->id }}">{{ ucfirst($j->name )}}</option>
                                            @endforeach
                                        </select>
                                    </div>--}}


                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-dark">Əlavə et</button>
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
            $('.select2').select2()
            //Initialize Select2 Elements
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            })
        })
    </script>
@endpush

