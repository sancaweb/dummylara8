@extends('layouts.app')
@section('content')
    <section class="content mt-4">
        <div class="container-fluid">
            @if (Session::has('messageAlert'))
                <div class="alert alert-{{ Session::get('alertClass') }} alert-dismissible fade show" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h5><i class="fas fa-exclamation-triangle"></i> PERHATIAN!</h5>
                    <strong>{{ Session::get('messageAlert') }}</strong>

                </div>
            @endif

            <div class="row">

                <div class="col-12">
                    <div class="card card-outline card-success">
                        <div class="card-header">
                            <h3 class="card-title" id="titlePage">{{ $pageTitle }}</h3>
                            <div class="card-tools">
                                <button id="btn-resetFilterReload" type="button" class="btn btn-sm btn-flat btn-warning">
                                    <i class="fas fa-sync"></i> &nbsp; Reset Filter & Reload
                                </button>
                                <button id="btn-pageReload" type="button" class="btn btn-sm btn-flat btn-success">
                                    <i class="fas fa-sync"></i> &nbsp; Reload
                                </button>

                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-flat btn-primary dropdown-toggle"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-tools"></i>&nbsp;Tools
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right">

                                        <a href="{{ route('page.create') }}" class="dropdown-item">
                                            <i class="fas fa-plus"></i> Tambah Page
                                        </a>

                                        <button id="btn-filter" type="button" class="dropdown-item">
                                            <i class="fas fa-sync"></i> &nbsp; Filter
                                        </button>

                                    </div>
                                </div>



                            </div> <!-- ./card-tools -->
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table id="table-pages" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>NO</th>
                                        <th>Judul</th>
                                        <th>Penulis</th>
                                        <th>Published Date</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>

                                </tbody>
                                <tfoot>
                                </tfoot>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div> <!-- ./end .col-12 -->
            </div>
            <!-- ./Form -->
        </div>
        <!-- ./ .container-fluid -->
    </section>
    @include('master.page.modalFilter')
@endsection
