<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RouteHandler extends Controller
{
    /**
     * The / page
     *
     * @return Response
     */
    public function index()
    {
        return view('welcome');
    }

    /**
     * Open page by Slug.
     *
     * @param string $slug
     * 
     * @return View
     */
    public function page($slug = '')
    {
        $data['page'] = \App\Models\Content::page()->whereSlug($slug)->firstOrFail();

        return view('page', $data);
    }
}
