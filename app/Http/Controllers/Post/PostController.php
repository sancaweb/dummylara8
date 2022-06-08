<?php

namespace App\Http\Controllers\Post;

use Carbon\Carbon;
use App\Models\Post\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Post\Category;

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
        $dataPage = [
            "pageTitle" => "Data Post",
            "page" => "post",
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
            "pageTitle" => "Data Post",
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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

    public function datatable(Request $request)
    {

        $columns = array(
            0 => 'id_post',
            1 => 'title',
            2 => 'post_created_by',
            3 => 'category_name',
            4 => '',
            5 => 'published_date',
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

        // $tglFilter = $request->input('tglFilter');

        // if (!empty($tglFilter)) {
        //     $exp = explode("-", $tglFilter);
        //     $startDateFilter = str_replace('/', '-', trim($exp[0]));
        //     $endDateFilter = str_replace('/', '-', trim($exp[1]));

        //     $dataFilter['startDateFilter'] = Carbon::parse($startDateFilter)->format('Y-m-d');
        //     $dataFilter['endDateFilter'] = Carbon::parse($endDateFilter)->format('Y-m-d');
        // }


        // $noSoFilter = $request->input('noSoFilter');
        // if (!empty($noSoFilter)) {

        //     $dataFilter['noSoFilter'] = $noSoFilter;
        // }

        // $noLoFilter = $request->input('noLoFilter');
        // if (!empty($noLoFilter)) {
        //     $dataFilter['noLoFilter'] = $noLoFilter;
        // }

        // $bbmIdFilter = $request->input('bbmIdFilter');
        // if (!empty($bbmIdFilter)) {
        //     $dataFilter['bbmIdFilter'] = $bbmIdFilter;
        // }

        // $driverFilter = $request->input('driverFilter');
        // if (!empty($driverFilter)) {
        //     $dataFilter['driverFilter'] = $driverFilter;
        // }

        // $noPolFilter = $request->input('noPolFilter');
        // if (!empty($noPolFilter)) {
        //     $dataFilter['noPolFilter'] = $noPolFilter;
        // }

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

                $no++;
                $nestedData['no'] = $no;
                $nestedData['title'] = $data->title;
                $nestedData['post_created_by'] = $data->post_created_by;
                $nestedData['category_name'] = $data->category_name;
                $nestedData['tags'] = $tags;
                $nestedData['published_date'] = Carbon::parse($data->published_date)->format('d M Y H:i:s');


                $nestedData['action'] = '
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary btn-flat dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Action
                                    </button>
                                    <div class="dropdown-menu">
                                        <button data-id="' . $data->encryptedId() . '" class="dropdown-item btn-edit">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>

                                        <button type="button" data-id="' . $data->encryptedId() . '" class="dropdown-item btn-delete">
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
