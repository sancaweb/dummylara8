<?php

namespace App\Models\Master;

use App\Models\User;
use App\Traits\WhosTrait;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Page extends Model
{
    use HasFactory, SoftDeletes, Sluggable, WhosTrait;

    protected $table = 'pages';
    protected $primaryKey = 'id_page';
    protected $fillable = [
        'title', 'slug', 'content', 'status',
        'published_date', 'featured_image', 'thumb', 'created_by', 'updated_by'
    ];

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
        return Crypt::encryptString($this->id_page);
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

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }


    /**
     * FOR DATATABLE
     */

    private static function _query($dataFilter)
    {

        $data = self::select(
            'pages.id_page as id_page',
            'pages.title as title',
            'pages.content as content',
            'pages.status as status',
            'pages.published_date as published_date',
            'users.name as page_created_by',

        )->leftJoin('users', 'pages.created_by', '=', 'users.id');

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
