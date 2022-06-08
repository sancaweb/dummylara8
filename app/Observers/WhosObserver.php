<?php

namespace App\Observers;

class WhosObserver
{

    public function creating($model)
    {
        $userId = auth()->user() !== null ? auth()->user()->id : '1';
        $model->created_by = $userId;
        $model->updated_by = $userId;
    }

    public function updating($model)
    {
        $userId = auth()->user() !== null ? auth()->user()->id : '1';
        $model->updated_by = $userId;
    }
}
