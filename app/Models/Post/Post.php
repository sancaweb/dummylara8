<?php

namespace App\Models\Post;

use App\Traits\WhosTrait;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Database\Eloquent\Model;
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
        'published_date', 'created_by', 'updated_by'
    ];

    //Anonymous Global Scope
    protected static function booted()
    {
        static::addGlobalScope('statusPost', function (Builder $builder) {
            $builder->where('status', '=', 'published');
        });
    }

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
        return Crypt::encryptString($this->id_piutang);
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id_category');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'post_tag', 'post_id', 'tag_id');
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

        // if (isset($dataFilter['startDateFilter'])) {
        //     $startDateFilter = $dataFilter['startDateFilter'];
        //     $data->whereDate('tgl_kedatangan', '>=', $startDateFilter);
        // }

        // if (isset($dataFilter['endDateFilter'])) {
        //     $endDateFilter = $dataFilter['endDateFilter'];
        //     $data->whereDate('tgl_kedatangan', '<=', $endDateFilter);
        // }

        // if (isset($dataFilter['noSoFilter'])) {
        //     $noSoFilter = $dataFilter['noSoFilter'];
        //     $data->where('no_so', $noSoFilter);
        // }

        // if (isset($dataFilter['noLoFilter'])) {
        //     $noLoFilter = $dataFilter['noLoFilter'];
        //     $data->where('no_lo', $noLoFilter);
        // }

        // if (isset($dataFilter['bbmIdFilter'])) {
        //     $bbmId = $dataFilter['bbmIdFilter'];
        //     $data->where('bbm_id', $bbmId);
        // }

        // if (isset($dataFilter['driverFilter'])) {
        //     $driverFilter = $dataFilter['driverFilter'];
        //     $data->where('driver_name', $driverFilter);
        // }

        // if (isset($dataFilter['noPolFilter'])) {
        //     $noPolFilter = $dataFilter['noPolFilter'];
        //     $data->where('no_pol', $noPolFilter);
        // }

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
