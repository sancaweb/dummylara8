<?php

namespace App\Http\Controllers\Ajax;

use Exception;
use App\Models\Post\Tag;
use App\Models\Post\Post;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormat;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Encryption\DecryptException;

class PostAjax extends Controller
{
    private function _decryptString($string)
    {
        try {
            return Crypt::decryptString($string);
        } catch (DecryptException $error) {
            return false;
        }
    }

    public function index()
    {
        return ResponseFormat::success([
            'data' => "What do you want..?"
        ], "What do you want..?");
    }

    public function getTags(Request $request)
    {
        $search = $request->input('search');
        $limit = $request->input('page_limit');

        $getTags = Tag::orderBy('name', 'ASC');
        if ($search != '') {
            $getTags->where('name', 'LIKE', "%{$search}%");
        }

        $dataTags = $getTags->get();

        $dataJson = [];

        foreach ($dataTags as $tag) {
            $dataJson[] = [
                'id' => $tag->id_tag,
                'text' => $tag->name,
            ];
        }

        return response()->json($dataJson);
    }

    public function select2GetTagByPost($encryptedPost)
    {


        $idPost = $this->_decryptString($encryptedPost);
        if (!$idPost) {
            return ResponseFormat::error([
                'error' => "Error Decrypt ID Post"
            ], "Error Decrypt ID Post", 400);
        }

        $getPost = Post::find($idPost);
        $dataTags = [];
        $getTags = $getPost->tags;
        foreach ($getTags as $tag) {
            $dataTags[] = [
                'id' => $tag->id_tag,
                'text' => $tag->name
            ];
        }

        return response()->json($dataTags);
    }

    public function changeStatus(Request $request)
    {
        $dataValidate['idPost'] = ['required'];
        $dataValidate['status'] = ['required'];

        $validator = Validator::make($request->all(), $dataValidate);

        if ($validator->fails()) {
            return ResponseFormat::error([
                'errorValidator' => $validator->messages(),
            ], "Error Validator", 402);
        }

        $encryptedPost = $request->idPost;

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
                $getStatus = $request->status;

                if ($getStatus == 'draft') {
                    $status = "published";
                } else {
                    $status = "draft";
                }

                $getPost->status = $status;
                $getPost->save();

                activity('post_management')->withProperties($getPost)->performedOn($getPost)->log('Update Post');

                DB::commit();
                return ResponseFormat::success([
                    'message' => "Status Post Updated",
                ], "Status Post Updated");
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
}
