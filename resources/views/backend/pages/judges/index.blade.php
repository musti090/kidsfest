@extends('backend.layouts.app')
@section('title','Münsifə aid iştirakçılar')
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
                            <h3 class="card-title">İştirakçıları tap</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->

                        <form action="{{ route('backend.judges.search') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Nominasiyalar</label>
                                    <select name="nomination_id" class="form-control select2bs4 required" placeholder="Nominasiyanı seçin"
                                            id="sub_category_name">
                                        <option value="0" disabled selected hidden>Nominasiyanı seçin</option>
                                        @foreach( $nominations as $n)
                                            <option value="{{ $n->id }}">{{ ucfirst($n->name )}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group hide-precincts">
                                    <label>Münsiflər</label>
                                    <select name="judge_id" class="form-control  required" placeholder="Münsifi seçin" id="sub_category">
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="date_time">Tarix</label>
                                    <input id="date_time" name="date_time" type="datetime-local"
                                           class="form-control">
                                </div>

                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer">
                                <button type="submit" class="btn btn-dark">Axtar</button>
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
    <script>
        $(document).ready(function () {
            $('#sub_category_name').on('change', function () {
                $('.hide-precincts').css("display", "block");
                let id = $(this).val();
                $('#sub_category').empty();
                $('#sub_category').append(`<option value="0" disabled selected>Yüklənir...</option>`);
                $.ajax({
                    type: 'GET',
                    url: 'judges/' + id,
                    success: function (response) {
                        var response = JSON.parse(response);
                        console.log(response);
                        $('#sub_category').empty();
                        $('#sub_category').append(`<option value="0" disabled selected>Münsifi seçin</option>`);
                        response.forEach(element => {
                            $('#sub_category').append(`<option value="${element['id']}">${element['name']}</option>`);
                        });
                    }
                });
            });
        });
    </script>
@endpush

