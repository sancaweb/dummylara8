<?php

namespace App\Http\Controllers;

use App\Models\Post\Post;
use App\Models\User;
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
        // $data = Post::with(['category', 'tags:slug,name'])->whereHas('tags', function ($q) {
        //     $q->where('slug', 'berita-bola');
        // })->get();

        $data = Post::select(
            'posts.id_post as id_post',
            'posts.title as title',
            'posts.content as content',
            'posts.status as status',
            'posts.published_date as published_date',
            'posts.created_by as post_created_by',

            'categories.name as category_name',

        )->leftJoin('categories', 'posts.category_id', '=', 'categories.id_category')
            ->with('tags:id_tag,slug,name')->get();






        $dataJson = [
            'data' => $data,
            'lastArray' => array_key_last($data->toArray())
        ];
        // dd($users);

        return response()->json($dataJson);
    }
}
