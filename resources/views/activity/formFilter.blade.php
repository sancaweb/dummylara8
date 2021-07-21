<!-- Form -->
<div class="row">
    <div class="col-12">
        <div id="cardFormActivity" class="card card-outline card-success collapsed-card">
            <div class="card-header">

                <h3 class="card-title"><i class="fas fa-filter"></i> &nbsp;Filter Log</h3>


                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body collapse">
                <div class="form-group row">
                    <div class="col-sm-6 mb-3 mb-sm-0">
                        <select id="userAct" class="form-control form-control-lg">
                            <option value="">-= Select User =-</option>
                            @foreach ($userAct as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                            <option value="system">System</option>
                        </select>
                    </div>
                    <div class="col-sm-6 mb-3 mb-sm-0">
                        <select id="logNameAct" class="form-control form-control-lg">
                            <option value="">-= Select Log Name =-</option>
                            @foreach ($logNameAct as $log)
                            <option value="{{ $log->log_name }}">{{ $log->log_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-sm-6 mb-3 mb-sm-0">
                        <input type="text" id="dateRangeFilter" value="" class="form-control rangeDate" placeholder="Waktu">
                    </div>
                    <div class="col-sm-6 mb-3 mb-sm-0">
                        <textarea class="form-control form-control-lg" id="descAct" placeholder="Description"></textarea>
                    </div>
                </div>


            </div>
            <!-- /.card-body -->
            <div class="card-footer ">
                <div class="row">
                    <div class="col-md-6"></div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <button type="button" id="btn-resetFilter" class="btn btn-success btn-user btn-block">
                                <i class="fas fa-sync"></i> Reset
                            </button>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <button type="button" id="btn-filter" class="btn btn-primary btn-user btn-block">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                        </div>
                    </div>
                </div>
            </div> <!-- ./card-footer -->

        </div> <!-- ./card -->
    </div> <!-- ./ col-12 -->
</div> <!-- ./row -->
