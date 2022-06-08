@extends('layouts.app')

@section('content')
<section class="content mt-4">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card card-outline card-success">
                    <div class="card-header">
                        <h3 class="card-title">Data User Trash</h3>
                        <div class="card-tools">
                            <a href="{{ route('user') }}" type="button" class="btn btn-sm btn-flat btn-success">
                                <i class="nav-icon fas fa-users-cog"></i> &nbsp; Data Users
                            </a>
                            <button id="btn-userReload" type="button" class="btn btn-sm btn-flat btn-primary">
                                <i class="fas fa-sync"></i> &nbsp; Reload
                            </button>
                        </div>
                    </div> <!-- /.card-header -->

                    <div class="card-body">
                        <table id="table-userTrash" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>NO</th>
                                    <th>Foto</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Username</th>
                                    <th>Role</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>

                            </tbody>
                            <tfoot>
                            </tfoot>
                        </table>
                    </div> <!-- /.card-body -->




                </div> <!-- ./card -->

            </div>
        </div>
    </div>
</section>
@endsection
