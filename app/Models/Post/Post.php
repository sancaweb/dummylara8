<?php

namespace App\Models\Post;

use App\Traits\WhosTrait;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory, WhosTrait, SoftDeletes, Sluggable;

    protected $table = 'posts';
    protected $primaryKey = 'id_post';
    protected $fillable = [
        'title', 'slug', 'content', 'category_id', 'status',
        'published_date', 'featured_image', 'thumb', 'created_by', 'updated_by'
    ];

    //Anonymous Global Scope
    // protected static function booted()
    // {
    //     static::addGlobalScope('statusPost', function (Builder $builder) {
    //         $builder->where('status', '=', 'published');
    //     });
    // }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function encryptedId()
    {
        return Crypt::encryptString($this->id_post);
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id_category');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'post_tag', 'post_id', 'tag_id');
    }

    public function takeImage()
    {
        if ($this->featured_image === null) {
            return asset("images/no-image.png");
        } else {
            $exist = Storage::exists($this->featured_image);

            if ($exist) {
                return asset("storage/" . $this->featured_image);
            } else {
                return asset("images/no-image.png");
            }
        }
    }

    public function takeThumb()
    {
        if ($this->thumb === null) {
            return asset("images/no-image.png");
        } else {
            $exist = Storage::exists($this->thumb);

            if ($exist) {
                return asset("storage/" . $this->thumb);
            } else {
                return asset("images/no-image.png");
            }
        }
    }

    /**
     * DATATABLE
     */

    /**
     * FOR DATATABLE
     */

    private static function _query($dataFilter)
    {

        $data = self::select(
            'posts.id_post as id_post',
            'posts.title as title',
            'posts.content as content',
            'posts.status as status',
            'posts.published_date as published_date',
            'users.name as post_created_by',

            'categories.name as category_name',

        )->leftJoin('categories', 'posts.category_id', '=', 'categories.id_category')
            ->leftJoin('users', 'posts.created_by', '=', 'users.id')
            ->with('tags:id_tag,slug,name');

        if (isset($dataFilter['startDateFilter'])) {
            $startDateFilter = $dataFilter['startDateFilter'];
            $data->whereDate('published_date', '>=', $startDateFilter);
        }

        if (isset($dataFilter['endDateFilter'])) {
            $endDateFilter = $dataFilter['endDateFilter'];
            $data->whereDate('published_date', '<=', $endDateFilter);
        }

        if (isset($dataFilter['titleContentFilter'])) {
            $titleContentFilter = $dataFilter['titleContentFilter'];
            $data->where('title', 'LIKE', "%{$titleContentFilter}%")
                ->orWhere('content', 'LIKE', "%{$titleContentFilter}%");
        }

        if (isset($dataFilter['catFilter'])) {
            $catFilter = $dataFilter['catFilter'];
            $data->where('category_id', $catFilter);
        }

        if (isset($dataFilter['tagFilter'])) {
            $tagFilter = $dataFilter['tagFilter'];
            $data->whereHas('tags', function ($q) use ($tagFilter) {
                $q->where('tag_id', $tagFilter);
            });
        }

        if (isset($dataFilter['userFilter'])) {
            $userFilter = $dataFilter['userFilter'];
            $data->where('created_by', $userFilter);
        }

        if (isset($dataFilter['statusFilter'])) {
            $statusFilter = $dataFilter['statusFilter'];
            $data->where('status', $statusFilter);
        }

        if (isset($dataFilter['search'])) {
            $search = $dataFilter['search'];
            $data->where(function ($query) use ($search) {
                $query->where('title', 'LIKE', "%{$search}%")
                    ->orWhere('content', 'LIKE', "%{$search}%");
            });
        }

        $result = $data;
        return $result;
    }

    public static function getData($dataFilter, $settings)
    {
        return self::_query($dataFilter)->offset($settings['start'])
            ->limit($settings['limit'])
            ->orderBy($settings['order'], $settings['dir'])
            ->get();
    }

    public static function countData($dataFilter)
    {
        return self::_query($dataFilter)->count();
    }
}
