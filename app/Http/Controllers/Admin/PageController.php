<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Yajra\Datatables\Datatables;
use App\Http\Requests\PageRequest;
use Illuminate\Http\Request;
use App\Models\Content;

/**
 * Admin Page Management.
 */
class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['table'] = [
            'table_url' => route("page.data"),
            'columns' => [
                [
                    'name' => 'formatted_id',
                    'label' => 'ID',
                ],
                [
                    'name' => 'slug',
                    'label' => 'Slug',
                ],
                [
                    'name' => 'title',
                    'label' => 'Title',
                ],
                [
                    'name' => 'action',
                    'label' => '#',
                ],
            ]
        ];
        
        return view('admin.page.index', $data);
    }

    /**
     * JSON Data for DataTable.
     *
     * @return DataTable
     */
    public function getData()
    {
        $query = Content::page()->select(['id', 'title', 'slug']);

        return Datatables::of($query)->addColumn('formatted_id', function($item){
            return '<strong>' . $item->formatted_id . '</strong>';
        })->addColumn('action', function($item){
            
            $string = '';
            $string .= '<a href="' . route('page.edit', $item->id) . '"><button title="Edit" class="btn btn-icon btn-sm btn-success waves-effect waves-light" style="margin-right: 5px;"><i class="fa fa-pencil"></i></button></a>';

            return $string;
        })->rawColumns(['action', 'formatted_id'])->make();
    }

    /**
     * Prepare Data
     *
     * @param Request $request
     * 
     * @return array
     */
    public function prepareData($request)
    {
        $payload = $request->only([
            'title',
            'content_text',
        ]);

        return $payload;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return redirect(route('page.edit', $id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['object'] = Content::page()->findOrFail($id);
        $data['fields'] = Content::mappingFieldFormPage();

        return view('admin.page.form', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PageRequest $request, $id)
    {
        $payload = $this->prepareData($request);
        $object = Content::page()->findOrFail($id)->update($payload);

        return redirect()->back()->with('status', 'Page successfully updated.');
    }
}
