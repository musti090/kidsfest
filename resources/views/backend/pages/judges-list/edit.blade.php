@extends('backend.layouts.app')
@section('title','Münsifi yenilə')
@section('content')
    <section class="content">

        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-6">

                    <div class="card card-dark">
                        <div class="card-header">
                            <h3 class="card-title">Yenilə</h3>
                        </div>
                        <form action="{{ route('backend.judges-list.update',$data->id) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="name">Münsifin adı,soyadı</label>
                                    <input id="name" name="name" type="text" value="{{ $data->name }}"
                                           class="form-control">
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <button type="submit" class="btn btn-dark">Yenilə</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        
        </div><!-- /.container-fluid -->
    </section>
@endsection


