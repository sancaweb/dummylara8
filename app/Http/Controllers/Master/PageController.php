<?php

namespace App\Http\Controllers\Master;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Master\Page;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormat;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Encryption\DecryptException;

class PageController extends Controller
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
        $penulis = User::role(['admin', 'super admin'])->get();
        $dataPage = [
            "pageTitle" => "Data Page",
            "page" => "page",
            "penulis" => $penulis
        ];

        return view('master.page.index', $dataPage);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $dataPage = [
            "pageTitle" => "Input Page",
            "page" => "page",
        ];

        return view('master.page.inputForm', $dataPage);
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


        $publishedDate = $request->input('published_date');
        $status = $request->status;

        DB::beginTransaction();
        try {


            $newPage = Page::create([
                'title' => $title,
                'content' => $content,
                'published_date' => Carbon::createFromFormat('d-m-Y H:i:s', $publishedDate)->format('Y-m-d H:i:s'),
                'status' => $status,
                'featured_image' => $featuredImage,
                'thumb' => $thumb,
            ]);


            activity('page_management')->withProperties($newPage)->performedOn($newPage)->log('Create Page');

            DB::commit();
            return ResponseFormat::success([
                'message' => "Page Created",
            ], "Page Created");
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
    public function show($encryptedPage)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($encryptedPage)
    {

        $idPage = $this->_decryptString($encryptedPage);
        if (!$idPage) {


            return redirect()->route('page')->with(
                [
                    'messageAlert' => "Error Decrypt ID Page",
                    "alertClass" => "danger"
                ]
            );
        }

        $getPage = Page::find($idPage);
        if ($getPage) {

            $dataPage = [
                "id_page" => $getPage->encryptedId(),
                "title" => $getPage->title,
                "slug" => $getPage->slug,
                "content" => $getPage->content,
                "status" => $getPage->status,
                "featured_image" => $getPage->takeImage(),
                "thumb" => $getPage->takeThumb(),
                "published_date" => Carbon::parse($getPage->published_date)->format('d-m-Y H:i:s'),
            ];



            $dataPage = [
                "pageTitle" => "Edit Page",
                "page" => "editPage",
                "dataPage" => $dataPage,
            ];


            return view('master.page.editForm', $dataPage);
        } else {

            return redirect()->route('page')->with(
                [
                    'messageAlert' => "Data Page tidak ditemukan",
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
    public function update(Request $request, $encryptedPage)
    {

        $idPage = $this->_decryptString($encryptedPage);
        if (!$idPage) {
            return ResponseFormat::error([
                'error' => "Error Decrypt ID Page"
            ], "Error Decrypt ID Page", 400);
        }

        $getPage = Page::find($idPage);

        if ($getPage) {

            $dataValidate['title'] = 'required';
            $dataValidate['content'] = 'required';
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



            $publishedDate = $request->input('published_date');
            $status = $request->status;

            DB::beginTransaction();
            try {

                //update Page
                $getPage->update([
                    'title' => $title,
                    'content' => $content,
                    'status' => $status,
                    'published_date' => Carbon::createFromFormat('d-m-Y H:i:s', $publishedDate)->format('Y-m-d H:i:s'),
                    'featured_image' => $featuredImage,
                    'thumb' => $thumb,
                ]);


                activity('page_management')->withProperties($getPage)->performedOn($getPage)->log('Update Page');

                DB::commit();
                return ResponseFormat::success([
                    'message' => "Page Updated",
                ], "Page Updated");
            } catch (Exception $error) {
                DB::rollBack();
                return ResponseFormat::error([
                    'error' => $error->getMessage() . '-' . $error->getFile() . '-' . $error->getLine()
                ], "Something went wrong", 400);
            }
        } else { //else $getPage
            return ResponseFormat::error([
                'error' => "Page Not Found"
            ], "Data Page tidak ditemukan", 404);
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

    public function delete($encryptedPage)
    {
        $idPage = $this->_decryptString($encryptedPage);
        if (!$idPage) {
            return ResponseFormat::error([
                'error' => "Error Decrypt ID Page"
            ], "Error Decrypt ID Page", 400);
        }

        $getPage = Page::find($idPage);
        if ($getPage) {
            DB::beginTransaction();
            try {
                $getPage->delete();
                activity('page_management')->withProperties($getPage)->performedOn($getPage)->log('Delete Page');

                DB::commit();
                return ResponseFormat::success([
                    'message' => "Page Deleted",
                ], "Page Deleted");
            } catch (Exception $error) {
                DB::rollBack();
                return ResponseFormat::error([
                    'error' => $error->getMessage() . '-' . $error->getFile() . '-' . $error->getLine()
                ], "Something went wrong", 400);
            }
        } else {
            return ResponseFormat::error([
                'error' => "Page Not Found"
            ], "Data Page tidak ditemukan", 404);
        }
    }

    public function datatable(Request $request)
    {

        $columns = array(
            0 => 'id_page',
            1 => 'title',
            2 => 'page_created_by',
            3 => 'published_date',
            4 => 'status',
        );

        $totalData = Page::count();
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


        $userFilter = $request->input('userFilter');
        if (!empty($userFilter)) {
            $dataFilter['userFilter'] = $userFilter;
        }

        $statusFilter = $request->input('statusFilter');
        if (!empty($statusFilter)) {
            $dataFilter['statusFilter'] = $statusFilter;
        }

        //getData
        $pages = Page::getData($dataFilter, $settings);
        $totalFiltered = Page::countData($dataFilter);

        $dataTable = [];

        if (!empty($pages)) {
            $no = $start;
            foreach ($pages as $data) {


                $statusClass = 'btn-success';
                $status = "Published";
                if ($data->status == 'draft') {
                    $statusClass = 'btn-danger';
                    $status = "Draft";
                }

                $no++;
                $nestedData['no'] = $no;
                $nestedData['title'] = $data->title;
                $nestedData['page_created_by'] = $data->page_created_by;
                $nestedData['published_date'] = Carbon::parse($data->published_date)->format('d M Y H:i:s');
                $nestedData['status'] = '<button data-id="' . $data->encryptedId() . '" data-title="' . ucwords($data->title) . '" data-status="' . $data->status . '" class="btn btn-icon ' . $statusClass . ' btnStatus" data-toggle="tooltip" title="" data-original-title="Edit Status">
                <i class="fas fa-exchange-alt"></i>&nbsp; ' . $status . '</button>';


                $nestedData['action'] = '
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary btn-flat dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Action
                                    </button>
                                    <div class="dropdown-menu">
                                        <a href="' . route('page.edit', $data->encryptedId()) . '" class="dropdown-item">
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
