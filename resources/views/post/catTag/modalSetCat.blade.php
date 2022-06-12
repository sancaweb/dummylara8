<!-- Modal Filter-->
<div class="modal fade" id="modalSetCat" tabindex="-1" role="dialog" aria-labelledby="modalSetCat"
    aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleSetCat">Set Category</h5>
                <button type="button" class="close cancelSetCat">
                    <span aria-hidden="true">&times;</span>
                </button>

            </div>
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h5><i class="fas fa-exclamation-triangle"></i> PERHATIAN!</h5>
                <strong id="messageAlertSetCat"></strong>

            </div>
            <form data-jenis="catSet" action="" class="form-horizontal" id="formSetCat" method="post">
                @csrf
                <input type="hidden" name="_method" value="patch">

                <div class="modal-body">
                    <div class="form-group">
                        <label for="catSet">Category</label>
                        <select data-placeholder="Category" name="catSet" id="catSet" class="form-control">

                        </select>
                    </div>
                    <!-- /.form-group -->

                </div> <!-- ./modal Body -->
                <div class="modal-footer text-right">
                    <button type="button" class="btn btn-secondary btn-flat btn-danger cancelSetCat"><i
                            class="far fa-window-close"></i>&nbsp;Cancel</button>
                    <button type="submit" class="btn btn-primary btn-flat"><i class="fas fa-save"></i>&nbsp;Save
                        changes</button>

                </div>
            </form>
        </div>
    </div>
</div>
<!-- END Modal Filter -->
