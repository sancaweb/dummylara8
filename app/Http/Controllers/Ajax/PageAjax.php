<?php

namespace App\Http\Controllers\Ajax;

use App\Models\Master\Page;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormat;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Encryption\DecryptException;

class PageAjax extends Controller
{
    private function _decryptString($string)
    {
        try {
            return Crypt::decryptString($string);
        } catch (DecryptException $error) {
            return false;
        }
    }

    public function changeStatus(Request $request)
    {
        $dataValidate['idPage'] = ['required'];
        $dataValidate['status'] = ['required'];

        $validator = Validator::make($request->all(), $dataValidate);

        if ($validator->fails()) {
            return ResponseFormat::error([
                'errorValidator' => $validator->messages(),
            ], "Error Validator", 402);
        }

        $encryptedPage = $request->idPage;

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
                $getStatus = $request->status;

                if ($getStatus == 'draft') {
                    $status = "published";
                } else {
                    $status = "draft";
                }

                $getPage->status = $status;
                $getPage->save();

                activity('page_management')->withProperties($getPage)->performedOn($getPage)->log('Update Page');

                DB::commit();
                return ResponseFormat::success([
                    'message' => "Status Page Updated",
                ], "Status Page Updated");
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
}
