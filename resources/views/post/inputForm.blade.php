@extends('layouts.app')
@section('content')
    <section class="content mt-4">
        <div class="container-fluid">


            <div class="row">

                <div class="col-8">
                    <div class="card card-outline card-success">
                        <div class="card-header">
                            <h3 class="card-title">Input Post</h3>
                            <div class="card-tools">

                                <button id="btn-resetForm" type="button" class="btn btn-sm btn-flat btn-success">
                                    <i class="fas fa-sync"></i> &nbsp; Reset Form
                                </button>

                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">

                            <div class="form-group">
                                <label for="title">Title</label>
                                <input id="title" type="text" class="form-control" placeholder="Judul Post" name="title"
                                    required>
                            </div>
                            <!-- /.form-group -->

                            <div class="form-group">
                                <label for="content">Content</label>
                                <textarea class="form-control" name="content" id="content" cols="30" rows="10"></textarea>
                            </div>
                            <!-- /.form-group -->


                            </form>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card content-->
                </div> <!-- ./end .col-8 -->
                <div class="col-4">
                    <div class="card card-outline card-success">
                        <div class="card-header">
                            <h3 class="card-title">Options</h3>
                            <div class="card-tools">


                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="form-group">
                                <a id="linkFoto" href="<?= asset('images/no-image.png') ?>" data-lightbox="image-foto"
                                    data-title="featured Foto">
                                    <img id="imageReview" src="<?= asset('images/no-image.png') ?>" alt="Image Foto"
                                        style="width: 150px;height: 150px;" class="img-thumbnail img-fluid">
                                </a>
                            </div>

                            <div class="form-group">
                                <label for="inputFoto">Foto featured</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" name="foto" class="custom-file-input" id="inputFoto">
                                        <label class="custom-file-label" for="inputFoto" id="labelInputFoto">Choose
                                            file</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="categories">Category</label>
                                <select class="form-control select2" name="category_id" id="categories">
                                    <option value=""></option>
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat->id_category }}">{{ ucwords($cat->name) }}</option>
                                    @endforeach


                                </select>
                            </div>
                            <!-- /.form-group -->


                            <div class="form-group">
                                <label>Tags</label>
                                <select class="form-control select2" name="tags" id="tags">
                                    <option value=""></option>


                                </select>
                            </div>
                            <!-- /.form-group -->

                            <div class="form-group">
                                <label for="published_date">Published Date</label>
                                <input type="text" name="published_date" id="published_date" class="form-control">
                            </div>
                            <!-- /.form-group -->
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer text-right">
                            <button type="button" class="btn btn-secondary btn-flat btn-danger closeForm"
                                data-dismiss="modal"><i class="far fa-window-close"></i>&nbsp;Close</button>
                            <button type="submit" class="btn btn-primary btn-flat"><i class="fas fa-save"></i>&nbsp;Save
                                changes</button>
                        </div>
                    </div>
                    <!-- /.card sidebar-->
                </div>
            </div>
            <!-- ./row -->
        </div>
        <!-- ./ .container-fluid -->
    </section>
@endsection
