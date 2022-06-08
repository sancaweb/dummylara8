@extends('layouts.app')
@section('content')
    <section class="content mt-4">
        <div class="container-fluid">


            <div class="row">

                <div class="col-12">
                    <div class="card card-outline card-success">
                        <div class="card-header">
                            <h3 class="card-title">Data Post</h3>
                            <div class="card-tools">
                                <a href="{{ route('post.create') }}" class="btn btn-sm btn-flat btn-primary openForm">
                                    <i class="fas fa-plus"></i> Tambah Post
                                </a>
                                <button id="btn-postReload" type="button" class="btn btn-sm btn-flat btn-success">
                                    <i class="fas fa-sync"></i> &nbsp; Reload
                                </button>
                                @can('post delete')
                                    <a href="#" type="button" class="btn btn-sm btn-flat btn-danger">
                                        <i class="fas fa-trash"></i> &nbsp; Post Trash
                                    </a>
                                @endcan

                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="table-posts" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>NO</th>
                                        <th>Judul</th>
                                        <th>Penulis</th>
                                        <th>Kategori</th>
                                        <th>Tags</th>
                                        <th>Published Date</th>
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
@endsection
