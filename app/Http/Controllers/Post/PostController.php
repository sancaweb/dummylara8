<?php

namespace App\Http\Controllers\Post;

use Exception;
use Carbon\Carbon;
use App\Models\Post\Tag;
use App\Models\Post\Post;
use Illuminate\Http\Request;
use App\Models\Post\Category;
use App\Helpers\ResponseFormat;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Encryption\DecryptException;

class PostController extends Controller
{
    private function _decryptString($string)
    {
        try {
            return Crypt::decryptString($string);
        } catch (DecryptException $error) {
            return false;
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::orderBy('name', 'ASC')->get();
        $penulis = User::role(['admin', 'super admin'])->get();
        $dataPage = [
            "pageTitle" => "Data Post",
            "page" => "post",
            "categories" => $categories,
            "penulis" => $penulis
        ];

        return view('post.index', $dataPage);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::orderBy('name', 'ASC')->get();
        $dataPage = [
            "pageTitle" => "Input Post",
            "page" => "post",
            "categories" => $categories
        ];

        return view('post.inputForm', $dataPage);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $dataValidate['title'] = 'required';
        $dataValidate['content'] = 'required';
        $dataValidate['category_id'] = 'required';
        $dataValidate['published_date'] = ['required'];
        $dataValidate['status'] = ['required'];

        $validator = Validator::make($request->all(), $dataValidate);

        if ($validator->fails()) {
            return ResponseFormat::error([
                'errorValidator' => $validator->messages(),
            ], 'Error Validator', 402);
        }

        $title = $request->input('title');
        $content = $request->input('content');

        $getFeaturedImage = $request->input('featured_image');

        if (!empty($getFeaturedImage)) {
            $exImage = explode("/storage/", $getFeaturedImage);
            $featuredImage = $exImage[1];
        } else {
            $featuredImage = '';
        }

        $getThumb = $request->input('thumb');

        if (!empty($getThumb)) {
            $exThumb = explode("/storage/", $getThumb);
            $thumb = $exThumb[1];
        } else {
            $thumb = '';
        }


        $categoryId = $request->input('category_id');
        $publishedDate = $request->input('published_date');
        $status = $request->status;
        $tags = $request->tags;

        DB::beginTransaction();
        try {
            $listTags = [];
            foreach ($tags as $tag) {
                $cekTag = Tag::select('id_tag')->where('id_tag', $tag)->first();

                if ($cekTag) {
                    array_push($listTags, $tag);
                } else {
                    $newTag = Tag::create([
                        'name' => $tag
                    ]);

                    array_push($listTags, $newTag->id_tag);
                }
            }

            $newPost = Post::create([
                'title' => $title,
                'content' => $content,
                'category_id' => $categoryId,
                'published_date' => Carbon::createFromFormat('d-m-Y H:i:s', $publishedDate)->format('Y-m-d H:i:s'),
                'status' => $status,
                'featured_image' => $featuredImage,
                'thumb' => $thumb,
            ]);

            if (!empty($listTags)) {

                $newPost->tags()->attach($listTags);
            }

            activity('post_management')->withProperties($newPost)->performedOn($newPost)->log('Create Post');

            DB::commit();
            return ResponseFormat::success([
                'message' => "Post Created",
            ], "Post Created");
        } catch (Exception $error) {
            DB::rollBack();
            return ResponseFormat::error([
                'error' => $error->getMessage() . '-' . $error->getFile() . '-' . $error->getLine()
            ], "Something went wrong", 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($encryptedPost)
    {

        $idPost = $this->_decryptString($encryptedPost);
        if (!$idPost) {
            return ResponseFormat::error([
                'error' => "Error Decrypt ID Post"
            ], "Error Decrypt ID Post", 400);
        }

        $getPost = Post::find($idPost);
        if ($getPost) {

            $dataPost = [
                "id_post" => $getPost->encryptedId(),
                "title" => $getPost->title,
                "slug" => $getPost->slug,
                "content" => $getPost->content,
                "category_id" => $getPost->category_id,
                "status" => $getPost->status,
                "featured_image" => $getPost->takeImage(),
                "thumb" => $getPost->takeThumb(),
                "published_date" => $getPost->published_date,
            ];

            $dataTags = [];

            if ($getPost->tags()->exists()) {

                $getTags = $getPost->tags;
                foreach ($getTags as $tag) {
                    $dataTags[] = [
                        'id' => $tag->id_tag,
                        'text' => $tag->name
                    ];
                }
            }

            $categories = Category::orderBy('name', 'ASC')->get();
            $dataPage = [
                "pageTitle" => "Input Post",
                "page" => "editPost",
                "categories" => $categories,
                "dataPost" => $dataPost,
                "dataTags" => $dataTags
            ];

            return view('post.editForm', $dataPage);
        } else {
            return ResponseFormat::error([
                'error' => "Post Not Found"
            ], "Data Post tidak ditemukan", 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($encryptedPost)
    {
        $idPost = $this->_decryptString($encryptedPost);
        if (!$idPost) {


            return redirect()->route('post')->with(
                [
                    'messageAlert' => "Error Decrypt ID Post",
                    "alertClass" => "danger"
                ]
            );
        }

        $getPost = Post::find($idPost);
        if ($getPost) {

            $dataPost = [
                "id_post" => $getPost->encryptedId(),
                "title" => $getPost->title,
                "slug" => $getPost->slug,
                "content" => $getPost->content,
                "category_id" => $getPost->category_id,
                "status" => $getPost->status,
                "featured_image" => $getPost->takeImage(),
                "thumb" => $getPost->takeThumb(),
                "published_date" => Carbon::parse($getPost->published_date)->format('d-m-Y H:i:s'),
            ];


            $categories = Category::orderBy('name', 'ASC')->get();
            $dataPage = [
                "pageTitle" => "Input Post",
                "page" => "editPost",
                "categories" => $categories,
                "dataPost" => $dataPost,
            ];


            return view('post.editForm', $dataPage);
        } else {

            return redirect()->route('post')->with(
                [
                    'messageAlert' => "Data Post tidak ditemukan",
                    "alertClass" => "danger"
                ]
            );
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $encryptedPost)
    {
        $idPost = $this->_decryptString($encryptedPost);
        if (!$idPost) {
            return ResponseFormat::error([
                'error' => "Error Decrypt ID Post"
            ], "Error Decrypt ID Post", 400);
        }

        $getPost = Post::find($idPost);

        if ($getPost) {

            $dataValidate['title'] = 'required';
            $dataValidate['content'] = 'required';
            $dataValidate['category_id'] = 'required';
            $dataValidate['published_date'] = ['required'];

            $validator = Validator::make($request->all(), $dataValidate);

            if ($validator->fails()) {
                return ResponseFormat::error([
                    'errorValidator' => $validator->messages(),
                ], 'Error Validator', 402);
            }

            $title = $request->input('title');
            $content = $request->input('content');

            $getFeaturedImage = $request->input('featured_image');

            if (!empty($getFeaturedImage)) {
                $exImage = explode("/storage/", $getFeaturedImage);
                $featuredImage = $exImage[1];
            } else {
                $featuredImage = '';
            }

            $getThumb = $request->input('thumb');

            if (!empty($getThumb)) {
                $exThumb = explode("/storage/", $getThumb);
                $thumb = $exThumb[1];
            } else {
                $thumb = '';
            }


            $categoryId = $request->input('category_id');
            $publishedDate = $request->input('published_date');
            $status = $request->status;
            $tags = $request->tags;

            DB::beginTransaction();
            try {

                //update Post
                $getPost->update([
                    'title' => $title,
                    'content' => $content,
                    'category_id' => $categoryId,
                    'status' => $status,
                    'published_date' => Carbon::createFromFormat('d-m-Y H:i:s', $publishedDate)->format('Y-m-d H:i:s'),
                    'featured_image' => $featuredImage,
                    'thumb' => $thumb,
                ]);

                $listTags = [];
                foreach ($tags as $tag) {
                    $cekTag = Tag::select('id_tag')->where('id_tag', $tag)->first();

                    if ($cekTag) {
                        array_push($listTags, $tag);
                    } else {
                        $newTag = Tag::create([
                            'name' => $tag
                        ]);

                        array_push($listTags, $newTag->id_tag);
                    }
                }

                if (!empty($listTags)) {
                    $getPost->tags()->sync($listTags);
                }

                activity('post_management')->withProperties($getPost)->performedOn($getPost)->log('Update Post');

                DB::commit();
                return ResponseFormat::success([
                    'message' => "Post Updated",
                ], "Post Updated");
            } catch (Exception $error) {
                DB::rollBack();
                return ResponseFormat::error([
                    'error' => $error->getMessage() . '-' . $error->getFile() . '-' . $error->getLine()
                ], "Something went wrong", 400);
            }
        } else { //else $getPost
            return ResponseFormat::error([
                'error' => "Post Not Found"
            ], "Data Post tidak ditemukan", 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function delete($encryptedPost)
    {
        $idPost = $this->_decryptString($encryptedPost);
        if (!$idPost) {
            return ResponseFormat::error([
                'error' => "Error Decrypt ID Post"
            ], "Error Decrypt ID Post", 400);
        }

        $getPost = Post::find($idPost);
        if ($getPost) {
            DB::beginTransaction();
            try {
                $getPost->delete();
                activity('post_management')->withProperties($getPost)->performedOn($getPost)->log('Delete Post');

                DB::commit();
                return ResponseFormat::success([
                    'message' => "Status Post Deleted",
                ], "Status Post Deleted");
            } catch (Exception $error) {
                DB::rollBack();
                return ResponseFormat::error([
                    'error' => $error->getMessage() . '-' . $error->getFile() . '-' . $error->getLine()
                ], "Something went wrong", 400);
            }
        } else {
            return ResponseFormat::error([
                'error' => "Post Not Found"
            ], "Data Post tidak ditemukan", 404);
        }
    }

    public function datatable(Request $request)
    {

        $columns = array(
            0 => 'id_post',
            1 => 'title',
            2 => 'post_created_by',
            3 => 'category_name',
            4 => '',
            5 => 'published_date',
            6 => 'status',
        );

        $totalData = Post::count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $settings['start'] = $start;
        $settings['limit'] = $limit;
        $settings['dir'] = $dir;
        $settings['order'] = $order;

        //filter-filter
        $dataFilter = [];

        $search = $request->input('search.value');
        if (!empty($search)) {
            $dataFilter['search'] = $search;
        }

        $tglFilter = $request->input('tglFilter');
        if (!empty($tglFilter)) {
            $exp = explode("-", $tglFilter);
            $startDateFilter = str_replace('/', '-', trim($exp[0]));
            $endDateFilter = str_replace('/', '-', trim($exp[1]));

            $dataFilter['startDateFilter'] = Carbon::parse($startDateFilter)->format('Y-m-d');
            $dataFilter['endDateFilter'] = Carbon::parse($endDateFilter)->format('Y-m-d');
        }


        //title or content
        $titleContentFilter = $request->input('titleContentFilter');
        if (!empty($titleContentFilter)) {
            $dataFilter['titleContentFilter'] = $titleContentFilter;
        }

        $catFilter = $request->input('catFilter');
        if (!empty($catFilter)) {
            $dataFilter['catFilter'] = $catFilter;
        }

        $tagFilter = $request->input('tagFilter');
        if (!empty($tagFilter)) {
            $dataFilter['tagFilter'] = $tagFilter;
        }

        $userFilter = $request->input('userFilter');
        if (!empty($userFilter)) {
            $dataFilter['userFilter'] = $userFilter;
        }

        $statusFilter = $request->input('statusFilter');
        if (!empty($statusFilter)) {
            $dataFilter['statusFilter'] = $statusFilter;
        }

        //getData
        $kedatangan = Post::getData($dataFilter, $settings);
        $totalFiltered = Post::countData($dataFilter);

        $dataTable = [];

        if (!empty($kedatangan)) {
            $no = $start;
            foreach ($kedatangan as $data) {

                $tags = '';
                if ($data->tags()->exists()) {
                    $dataTags = $data->tags;
                    $lastArray = array_key_last($dataTags->toArray());

                    foreach ($data->tags as $keyTag => $tag) {
                        if ($keyTag == $lastArray) {

                            $tags .= $tag->name;
                        } else {
                            $tags .= $tag->name . ', ';
                        }
                    }
                }

                $statusClass = 'btn-success';
                $status = "Published";
                if ($data->status == 'draft') {
                    $statusClass = 'btn-danger';
                    $status = "Draft";
                }

                $no++;
                $nestedData['no'] = $no;
                $nestedData['title'] = $data->title;
                $nestedData['post_created_by'] = $data->post_created_by;
                $nestedData['category_name'] = $data->category_name;
                $nestedData['tags'] = $tags;
                $nestedData['published_date'] = Carbon::parse($data->published_date)->format('d M Y H:i:s');
                $nestedData['status'] = '<button data-id="' . $data->encryptedId() . '" data-title="' . ucwords($data->title) . '" data-status="' . $data->status . '" class="btn btn-icon ' . $statusClass . ' btnStatus" data-toggle="tooltip" title="" data-original-title="Edit Status">
                <i class="fas fa-exchange-alt"></i>&nbsp; ' . $status . '</button>';


                $nestedData['action'] = '
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary btn-flat dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Action
                                    </button>
                                    <div class="dropdown-menu">
                                        <a href="' . route('post.edit', $data->encryptedId()) . '" class="dropdown-item">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>

                                        <button type="button" data-id="' . $data->encryptedId() . '" data-title="' . ucwords($data->title) . '" class="dropdown-item btn-delete">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </div>
                                </div>';

                $dataTable[] = $nestedData;
            }
        }

        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $dataTable,
            "order"           => $order,
            "dir" => $dir,
        );

        return response()->json($json_data, 200);
    }
}
