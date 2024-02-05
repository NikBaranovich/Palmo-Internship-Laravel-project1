<?php
namespace App\Services;

use Illuminate\Http\Request;

abstract class BaseService
{
    public function index(Request $request)
    {
        return $this->repository->sort(
            $request->input('sort_by'),
            $request->input('sort_order')
        );
    }

}
