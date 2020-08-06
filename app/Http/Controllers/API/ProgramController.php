<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Helper\ResponseFormatter;
use App\Models\Program\Category;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    /**
     * Get list categories.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCategories()
    {
        $categories = Category::orderBy('sort', 'asc')->where('is_hidden', false);

        if (!is_null(request()->is_featured)) {
            $categories = $categories->where('is_featured', boolval(request()->is_featured));
        }

        if (!is_null(request()->name)) {
            $categories = $categories->where('title', 'LIKE', '%' . request()->name . '%');
        }

        $categories = $categories->get()->map(function($item){
            return $item->response;
        });

        return ResponseFormatter::success($categories);
    }
}
