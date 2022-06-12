<?php

namespace App\Http\Controllers;

use App\Models\Post\Category;
use App\Models\User;
use App\Models\Post\Tag;
use App\Models\Post\Post;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Activitylog\Models\Activity;
use Spatie\Permission\Models\Permission;

class TestingController extends Controller
{
    public function index()
    {
        $posts = Post::whereIn('category_id', [4])->get();
        if ($posts->isEmpty()) {
            $hasil = "Data Kosong";
        } else {
            $hasil = "Data Ditemukan";
        }



        $dataJson = [
            'ketHasil' => $hasil,
            'data' => $posts
        ];
        // dd($users);

        return response()->json($dataJson);
    }
}
