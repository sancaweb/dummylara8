<!-- Modal Filter-->
<div class="modal fade" id="modalFilter" tabindex="-1" role="dialog" aria-labelledby="modalFilter"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Filter Data Penjualan BBM</h5>
                <button type="button" class="close closeFilter">
                    <span aria-hidden="true">&times;</span>
                </button>

            </div>
            <form class="form-horizontal" id="formFilter" method="post">

                <div class="modal-body">
                    @csrf
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label>Tanggal</label>
                                <div class="input-group " id="tglFilter" data-target-input="nearest">
                                    <input id="tglFilterField" type="text" class="form-control"
                                        placeholder="Tanggal Publish" data-target="#tglFilter" autocomplete="off">

                                    <div class="input-group-append" data-target="#tglFilter">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.form-group -->
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="titleContentFilter">Title / Content</label>
                                <input type="text" id="titleContentFilter" class="form-control" placeholder="Keyword">
                            </div>
                        </div>
                    </div><!-- ./end row -->

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="userFilter">Publisher</label>
                                <select id="userFilter" class="form-control">
                                    <option value="">--= Pilih Penulis =--</option>
                                    @foreach ($penulis as $pen)
                                        <option value="{{ $pen->encryptedId() }}">{{ $pen->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="statusFilter">Status</label>
                                <select id="statusFilter" class="form-control">
                                    <option value="">Status Page</option>
                                    <option value="published">Published</option>
                                    <option value="draft">Draft</option>
                                </select>
                            </div>
                        </div>
                    </div>

                </div> <!-- ./modal Body -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger closeFilter"><i
                            class="fa fa-times"></i>&nbsp;Close</button>
                    <button data-pagetitle="{{ $pageTitle }}" type="button" class="btn btn-success" id="resetFilter">
                        <i class="fas fa-sync"></i>&nbsp;Reset</button>
                    <button data-pagetitle="{{ $pageTitle }}" id="btn-proFilter" type="button"
                        class="btn btn-primary">
                        <i class="fas fa-filter"></i>&nbsp;Filter
                    </button>

                </div>
            </form>
        </div>
    </div>
</div>
<!-- END Modal Filter -->
