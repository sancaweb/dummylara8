<div class="modal fade" id="modalFormInput" tabindex="-1" role="dialog" aria-labelledby="modalFormInputLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form action="{{ route('user.store') }}" method="POST" id="formUser" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalFormInputLabel"><i class="fas fa-user-plus"></i>&nbsp; Add User
                    </h5>
                    <button type="button" class="close closeForm" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    <div class="form-group">
                        <a id="linkFoto" href="{{ asset('images/no-image.png') }}" data-lightbox="image-foto"
                            data-title="User Foto">
                            <img id="imageReview" src="{{ asset('images/no-image.png') }}" alt="Image Foto"
                                style="width: 150px;height: 150px;" class="img-thumbnail img-fluid">
                        </a>
                    </div>

                    <div class="form-group">
                        <label for="inputFoto">Foto User</label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" name="foto" class="custom-file-input" id="inputFoto">
                                <label class="custom-file-label" for="inputFoto">Choose file</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Nama</label>
                        <input type="text" class="form-control" name="nama" required>
                    </div>
                    <!-- /.form-group -->

                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" class="form-control" name="username" required>
                    </div>
                    <!-- /.form-group -->

                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    <!-- /.form-group -->

                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" class="form-control" name="password">
                    </div>
                    <!-- /.form-group -->

                    <div class="form-group">
                        <label>Confirm Password</label>
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation"
                            autocomplete="new-password">

                    </div>
                    <!-- /.form-group -->

                    <div class="form-group">
                        <label>Roles</label>
                        <select class="form-control select2" name="role" id="role">
                            <option value=""></option>
                            @foreach ($roles as $key => $role)
                                <option value="{{ $role }}">{{ ucwords($role) }}</option>
                            @endforeach

                        </select>

                    </div>
                    <!-- /.form-group -->

                </div> <!-- ./modal-body -->

                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary btn-flat btn-danger closeForm"
                        data-dismiss="modal"><i class="far fa-window-close"></i>&nbsp;Close</button>
                    <button type="submit" class="btn btn-primary btn-flat"><i class="fas fa-save"></i>&nbsp;Save
                        changes</button>

                </div>
            </div> <!-- ./modal-content -->
        </form>
    </div> <!-- ./modal-dialog -->
</div> <!-- ./modal -->
