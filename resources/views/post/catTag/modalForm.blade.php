<!-- Modal Filter-->
<div class="modal fade" id="modalFormCat" tabindex="-1" role="dialog" aria-labelledby="modalFormCat"
    aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleFormCat"></h5>
                <button type="button" class="close cancelFormCat">
                    <span aria-hidden="true">&times;</span>
                </button>

            </div>
            <form data-jenis="cat" action="{{ route('category.store') }}" class="form-horizontal" id="formCat"
                method="post">
                @csrf

                <div class="modal-body">
                    <div class="form-group">
                        <label for="cat_name">Name</label>
                        <input type="text" placeholder="Nama Category" name="cat_name" id="cat_name"
                            class="form-control" required>
                    </div>
                    <!-- /.form-group -->
                    <div class="form-group">
                        <label for="cat_slug">Slug</label>
                        <input type="text" placeholder="Slug" name="cat_slug" id="cat_slug" class="form-control"
                            required>
                    </div>
                    <!-- /.form-group -->

                </div> <!-- ./modal Body -->
                <div class="modal-footer text-right">
                    <button type="button" class="btn btn-secondary btn-flat btn-danger cancelFormCat"><i
                            class="far fa-window-close"></i>&nbsp;Cancel</button>
                    <button type="submit" class="btn btn-primary btn-flat"><i class="fas fa-save"></i>&nbsp;Save
                        changes</button>

                </div>
            </form>
        </div>
    </div>
</div>
<!-- END Modal Filter -->
