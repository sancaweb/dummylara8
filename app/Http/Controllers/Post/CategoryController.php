<?php

namespace App\Http\Controllers\Post;

use Exception;
use Illuminate\Http\Request;
use App\Models\Post\Category;
use App\Helpers\ResponseFormat;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dataPage = [
            "pageTitle" => "Data Category",
            "pageTitleTag" => "Data Tag",
            "page" => "catTags",
        ];

        return view('post.catTag.index', $dataPage);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $dataValidate['cat_name'] = 'required';
        $dataValidate['cat_slug'] = 'required';

        $validator = Validator::make($request->all(), $dataValidate);

        if ($validator->fails()) {
            return ResponseFormat::error([
                'errorValidator' => $validator->messages(),
            ], 'Error Validator', 402);
        }

        $name = $request->cat_name;

        DB::beginTransaction();
        try {
            $newCat = Category::create([
                'name' => ucwords($name),
                'slug' => $request->cat_slug
            ]);
            activity('category_management')->withProperties($newCat)->performedOn($newCat)->log('Create Category');

            DB::commit();
            return ResponseFormat::success([
                'message' => "Category Created",
            ], "Category Created");
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
        $dataCat = Category::find($id);

        return ResponseFormat::success([
            'dataCat' => $dataCat,
            'action' => route('category.update', $dataCat->id_category)
        ], "Category ditemukan");
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
        $dataValidate['cat_name'] = 'required';
        $dataValidate['cat_slug'] = 'required';

        $validator = Validator::make($request->all(), $dataValidate);

        if ($validator->fails()) {
            return ResponseFormat::error([
                'errorValidator' => $validator->messages(),
            ], 'Error Validator', 402);
        }

        $name = $request->cat_name;

        $getCat = Category::find($id);
        if ($getCat) {
            DB::beginTransaction();
            try {
                $getCat->name = $name;
                $getCat->slug = $request->cat_slug;
                $getCat->save();

                activity('category_management')->withProperties($getCat)->performedOn($getCat)->log('Update Category');

                DB::commit();
                return ResponseFormat::success([
                    'message' => "Category Updated",
                ], "Category Updated");
            } catch (Exception $error) {
                DB::rollBack();
                return ResponseFormat::error([
                    'error' => $error->getMessage() . '-' . $error->getFile() . '-' . $error->getLine()
                ], "Something went wrong", 400);
            }
        } else {
            return ResponseFormat::error([
                'error' => "Category Not Found"
            ], "Category Not Found", 404);
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
        $getCat = Category::find($id);
        if ($getCat) {
            if ($getCat->posts()->exists()) {
                return ResponseFormat::error([
                    'error' => "Categor masih memiliki post"
                ], "Category masih memiliki post", 402);
            } else {
                DB::beginTransaction();
                try {

                    $getCat->delete();

                    activity('category_management')->withProperties($getCat)->performedOn($getCat)->log('Delete Category');

                    DB::commit();
                    return ResponseFormat::success([
                        'message' => "Category Deleted",
                    ], "Category Deleted");
                } catch (Exception $error) {
                    DB::rollBack();
                    return ResponseFormat::error([
                        'error' => $error->getMessage() . '-' . $error->getFile() . '-' . $error->getLine()
                    ], "Something went wrong", 400);
                }
            }
        } else {
            return ResponseFormat::error([
                'error' => "Category Not Found"
            ], "Category Not Found", 404);
        }
    }

    public function datatable(Request $request)
    {

        $columns = array(
            0 => 'id_category',
            1 => 'name',
            2 => 'slug',
            3 => 'posts_count',
        );

        $totalData = Category::count();
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



        //getData
        $categories = Category::getData($dataFilter, $settings);
        $totalFiltered = Category::countData($dataFilter);

        $dataTable = [];

        if (!empty($categories)) {
            $no = $start;
            foreach ($categories as $data) {



                $no++;
                $nestedData['no'] = $no;
                $nestedData['name'] = ucwords($data->name);
                $nestedData['slug'] = $data->slug;
                $nestedData['posts'] = $data->posts_count;

                $nestedData['action'] = '
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary btn-flat dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Action
                                    </button>
                                    <div class="dropdown-menu">
                                        <button data-id="' . $data->id_category . '" class="dropdown-item btn-edit">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>

                                        <button type="button" data-name="' . $data->name . '" data-id="' . $data->id_category . '" class="dropdown-item btn-delete">
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
