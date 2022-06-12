<?php

namespace App\Http\Controllers\Post;

use Exception;
use App\Models\Post\Tag;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormat;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return redirect(route('category'));
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

        $dataValidate['tag_name'] = 'required';
        $dataValidate['tag_slug'] = 'required';

        $validator = Validator::make($request->all(), $dataValidate);

        if ($validator->fails()) {
            return ResponseFormat::error([
                'errorValidator' => $validator->messages(),
            ], 'Error Validator', 402);
        }

        $name = $request->tag_name;

        DB::beginTransaction();
        try {
            $newCat = Tag::create([
                'name' => ucwords($name),
                'slug' => $request->tag_slug
            ]);
            activity('tag_management')->withProperties($newCat)->performedOn($newCat)->log('Create Tag');

            DB::commit();
            return ResponseFormat::success([
                'message' => "Tag Created",
            ], "Tag Created");
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

        $dataTag = Tag::find($id);

        return ResponseFormat::success([
            'dataTag' => $dataTag,
            'action' => route('tag.update', $dataTag->id_tag)
        ], "Tag ditemukan");
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

        $dataValidate['tag_name'] = 'required';
        $dataValidate['tag_slug'] = 'required';

        $validator = Validator::make($request->all(), $dataValidate);

        if ($validator->fails()) {
            return ResponseFormat::error([
                'errorValidator' => $validator->messages(),
            ], 'Error Validator', 402);
        }

        $name = $request->tag_name;

        $getTag = Tag::find($id);
        if ($getTag) {
            DB::beginTransaction();
            try {
                $getTag->name = $name;
                $getTag->slug = $request->tag_slug;
                $getTag->save();

                activity('tag_management')->withProperties($getTag)->performedOn($getTag)->log('Update Tag');

                DB::commit();
                return ResponseFormat::success([
                    'message' => "Tag Updated",
                ], "Tag Updated");
            } catch (Exception $error) {
                DB::rollBack();
                return ResponseFormat::error([
                    'error' => $error->getMessage() . '-' . $error->getFile() . '-' . $error->getLine()
                ], "Something went wrong", 400);
            }
        } else {
            return ResponseFormat::error([
                'error' => "Tag Not Found"
            ], "Tag Not Found", 404);
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

        $getTag = Tag::find($id);
        if ($getTag) {

            DB::beginTransaction();
            try {

                $getTag->posts()->detach();
                $getTag->delete();

                activity('tag_management')->withProperties($getTag)->performedOn($getTag)->log('Delete Tag');

                DB::commit();
                return ResponseFormat::success([
                    'message' => "Tag Deleted",
                ], "Tag Deleted");
            } catch (Exception $error) {
                DB::rollBack();
                return ResponseFormat::error([
                    'error' => $error->getMessage() . '-' . $error->getFile() . '-' . $error->getLine()
                ], "Something went wrong", 400);
            }
        } else {
            return ResponseFormat::error([
                'error' => "Tag Not Found"
            ], "Tag Not Found", 404);
        }
    }

    public function datatable(Request $request)
    {

        $columns = array(
            0 => 'id_tag',
            1 => 'name',
            2 => 'slug',
            3 => 'posts_count',
        );

        $totalData = Tag::count();
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
        $tags = Tag::getData($dataFilter, $settings);
        $totalFiltered = Tag::countData($dataFilter);

        $dataTable = [];

        if (!empty($tags)) {
            $no = $start;
            foreach ($tags as $data) {

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
                                        <button data-id="' . $data->id_tag . '" class="dropdown-item btn-edit">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>

                                        <button type="button" data-name="' . $data->name . '" data-id="' . $data->id_tag . '" class="dropdown-item btn-delete">
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
