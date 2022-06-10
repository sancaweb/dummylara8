<?php

namespace App\Http\Controllers;

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
        $string = 'http://localhost:8000/storage/photos/coding-background.jpg';

        $split = explode("/storage/", $string);

        $url = $split[0];
        $file = $split[1];




        $dataJson = [
            'string' => $string,
            'url' => $url,
            'file' => $file
        ];
        // dd($users);

        return response()->json($dataJson);
    }
}
