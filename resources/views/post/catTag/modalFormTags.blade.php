<!-- Modal Filter-->
<div class="modal fade" id="modalFormTag" tabindex="-1" role="dialog" aria-labelledby="modalFormTag"
    aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleFormTag"></h5>
                <button type="button" class="close cancelFormTag">
                    <span aria-hidden="true">&times;</span>
                </button>

            </div>
            <form action="{{ route('tag.store') }}" class="form-horizontal" id="formTag" method="post">
                @csrf

                <div class="modal-body">
                    <div class="form-group">
                        <label for="tag_name">Name</label>
                        <input type="text" placeholder="Nama Tag" name="tag_name" id="tag_name" class="form-control"
                            required>
                    </div>
                    <!-- /.form-group -->
                    <div class="form-group">
                        <label for="tag_slug">Slug</label>
                        <input type="text" placeholder="Slug" name="tag_slug" id="tag_slug" class="form-control"
                            required>
                    </div>
                    <!-- /.form-group -->

                </div> <!-- ./modal Body -->
                <div class="modal-footer text-right">
                    <button type="button" class="btn btn-secondary btn-flat btn-danger cancelFormTag"><i
                            class="far fa-window-close"></i>&nbsp;Cancel</button>
                    <button type="submit" class="btn btn-primary btn-flat"><i class="fas fa-save"></i>&nbsp;Save
                        changes</button>

                </div>
            </form>
        </div>
    </div>
</div>
<!-- END Modal Filter -->
