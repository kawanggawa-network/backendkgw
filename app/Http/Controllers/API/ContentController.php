<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Helper\ResponseFormatter;
use Illuminate\Http\Request;
use App\Models\Content;

/**
 * API Content (FAQ, Page, Country, Article, Banner) Controller.
 */
class ContentController extends Controller
{
    /**
     * Get page by Slug.
     *
     * @param string $slug
     * @return Response
     */
    public function getPage($slug = '')
    {
        $page = Content::page()->whereSlug($slug)->first();
        if (is_null($page)) {
            return ResponseFormatter::error(404, 'Page not found.');
        }

        return ResponseFormatter::success($page->response_page);
    }
}
