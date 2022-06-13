@extends('layouts.app')
@section('content')
    <section class="content mt-4">
        <div class="container-fluid">


            <form id="formPage" action="{{ route('page.update', $dataPage['id_page']) }}" method="post">
                <div class="row">
                    @csrf
                    <input type="hidden" name="_method" value="patch">
                    <input type="hidden" id="id_page" value="{{ $dataPage['id_page'] }}" readonly>
                    <div class="col-8">
                        <div class="card card-outline card-success">
                            <div class="card-header">
                                <h3 class="card-title">{{ $pageTitle }}</h3>
                                <div class="card-tools">

                                    <a href="{{ route('page.edit', $dataPage['id_page']) }}"
                                        class="btn btn-sm btn-flat btn-success">
                                        <i class="fas fa-sync"></i> &nbsp; Reset Form
                                    </a>

                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">

                                <div class="form-group">
                                    <label for="title">Title</label>
                                    <input id="title" value="{{ $dataPage['title'] }}" type="text" class="form-control"
                                        placeholder="Judul Page" name="title" required>
                                </div>
                                <!-- /.form-group -->

                                <div class="form-group">
                                    <label for="content">Content</label>
                                    <textarea class="form-control" name="content" id="content" cols="30" rows="10">
                                        {{ $dataPage['content'] }}
                                    </textarea>
                                </div>
                                <!-- /.form-group -->


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
                                    <a id="linkFoto" href="{{ $dataPage['featured_image'] }}" data-lightbox="image-foto"
                                        data-title="featured Foto">
                                        <img id="imageReview" src="{{ $dataPage['featured_image'] }}" alt="Image Foto"
                                            style="width: 150px;height: 150px;" class="img-thumbnail img-fluid">
                                    </a>
                                </div>


                                <div class="form-group">
                                    <label for="inputFoto">Featured Image</label>
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <a id="inputFoto" data-input="inputPath" data-preview="imageReview"
                                                data-linkfoto="linkFoto" class="btn btn-primary">
                                                <i class="fa fa-picture-o"></i> Choose Image
                                            </a>
                                        </span>
                                        <input value="{{ $dataPage['featured_image'] }}" id="inputPath"
                                            class="form-control" type="text" name="featured_image" readonly>
                                    </div>
                                    <input value="{{ $dataPage['thumb'] }}" id="thumbImage" class="form-control"
                                        type="hidden" name="thumb" readonly>
                                </div>


                                <div class="form-group">
                                    <label for="published_date">Published Date </label>
                                    <div class="input-group" id="published_date" data-target-input="nearest">
                                        <input id="inputPublishedDate" type="text" class="form-control datetimepicker-input"
                                            value="{{ $dataPage['published_date'] }}" data-target="#published_date"
                                            name="published_date" required>

                                        <div class="input-group-append" data-target="#published_date"
                                            data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="far fa-clock"></i></div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.form-group -->

                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select name="status" id="status" class="form-control">
                                        @if ($dataPage['status'] == 'draft')
                                            <option value="published">Published</option>
                                            <option value="draft" selected>Draft</option>
                                        @else
                                            <option value="published" selected>Published</option>
                                            <option value="draft">Draft</option>
                                        @endif

                                    </select>
                                </div>
                                <!-- /.form-group -->
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer text-right">
                                <a href="{{ route('page') }}" class="btn btn-secondary btn-flat btn-danger "><i
                                        class="far fa-window-close"></i>&nbsp;Cancel</a>
                                <button type="submit" class="btn btn-primary btn-flat"><i
                                        class="fas fa-save"></i>&nbsp;Save
                                    changes</button>
                            </div>
                        </div>
                        <!-- /.card sidebar-->
                    </div>

                </div>
                <!-- ./row -->
            </form>
        </div>
        <!-- ./ .container-fluid -->
    </section>
@endsection
