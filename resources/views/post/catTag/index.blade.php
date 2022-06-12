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

                <div class="col-6">
                    <div class="card card-outline card-success">
                        <div class="card-header">
                            <h3 class="card-title">{{ $pageTitle }}</h3>
                            <div class="card-tools">
                                <button id="btn-catsReload" type="button" class="btn btn-sm btn-flat btn-success">
                                    <i class="fas fa-sync"></i> &nbsp; Reload
                                </button>
                                <button data-jenis="cat" id="btn-addCat" type="button"
                                    class="btn btn-sm btn-flat btn-primary">
                                    <i class="fas fa-plus"></i> &nbsp; Tambah Category
                                </button>

                            </div> <!-- ./card-tools -->
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table id="table-cats" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center">NO</th>
                                        <th class="text-center">Name</th>
                                        <th class="text-center">Slug</th>
                                        <th class="text-center">POSTS</th>
                                        <th class="text-center">Action</th>
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
                </div> <!-- ./end .col-6 cats -->

                <div class="col-6">
                    <div class="card card-outline card-success">
                        <div class="card-header">
                            <h3 class="card-title">{{ $pageTitleTag }}</h3>
                            <div class="card-tools">
                                <button id="btn-tagsReload" type="button" class="btn btn-sm btn-flat btn-success">
                                    <i class="fas fa-sync"></i> &nbsp; Reload
                                </button>
                                <button id="btn-addTag" type="button" class="btn btn-sm btn-flat btn-primary">
                                    <i class="fas fa-plus"></i> &nbsp; Tambah Tag
                                </button>

                            </div> <!-- ./card-tools -->
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table id="table-tags" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center">NO</th>
                                        <th class="text-center">Name</th>
                                        <th class="text-center">Slug</th>
                                        <th class="text-center">POSTS</th>
                                        <th class="text-center">Action</th>
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
                </div> <!-- ./end .col-tag -->


            </div> <!-- ./row -->
        </div>
        <!-- ./ .container-fluid -->
    </section>
    @include('post.catTag.modalForm')
    @include('post.catTag.modalSetCat')
    @include('post.catTag.modalFormTags')
@endsection
