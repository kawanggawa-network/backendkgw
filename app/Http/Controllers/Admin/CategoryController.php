<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Requests\PageRequest;
use App\Models\Program\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['table'] = [
            'table_url' => route("category.data"),
            'create' => [
                'url' => route("category.create"),
                'label' => 'Add Category',
            ],
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
                    'name' => 'featured_icon',
                    'label' => 'Featured',
                ],
                [
                    'name' => 'visibility_icon',
                    'label' => 'Visibility',
                ],
                [
                    'name' => 'action',
                    'label' => '#',
                ],
            ]
        ];

        return view('admin.category.index', $data);
    }

    /**
     * JSON Data for DataTable.
     *
     * @return DataTable
     */
    public function getData()
    {
        $query = Category::orderBy('sort', 'asc');

        return Datatables::of($query)->addColumn('formatted_id', function($item){
            return '<strong>' . $item->formatted_id . '</strong>';
        })->addColumn('action', function($item){
            $string = '';

            $before = Category::where('sort', '<', $item->sort)->orderBy('sort', 'desc')->first();
            if (!is_null($before)){
                $string .= '<a href="' . route('category.up', $item->id) . '"><button title="Up" class="btn btn-icon btn-sm btn-primary waves-effect waves-light" style="margin-right: 5px;"><i class="fa fa-arrow-up"></i></button></a>';
            }

            $after = Category::where('sort', '>', $item->sort)->orderBy('sort', 'asc')->first();
            if (!is_null($after)){
                $string .= '<a href="' . route('category.down', $item->id) . '"><button title="Down" class="btn btn-icon btn-sm btn-warning waves-effect waves-light" style="margin-right: 5px;"><i class="fa fa-arrow-down"></i></button></a>';
            }

            $string .= '<a href="' . route('category.edit', $item->id) . '"><button title="Edit" class="btn btn-icon btn-sm btn-success waves-effect waves-light" style="margin-right: 5px;"><i class="fa fa-pencil"></i></button></a>';

            $string .= '<button title="Hapus" class="btn btn-icon btn-sm btn-danger waves-effect waves-light delete"><i class="fa fa-trash"></i></button>';
            $string .= '<form action="' . route('category.destroy', $item->id) . '" method="POST">' . method_field('delete') . csrf_field() . '</form>';

            return $string;
        })->addColumn('featured_icon', function($item){

            $icon = '';
            if ($item->is_featured) {
                $icon = 'check';
            } else {
                $icon = 'times';
            }

            return '<span class="fa fa-' . $icon . '"></span>';

        })->addColumn('visibility_icon', function($item){

            $icon = '';
            if ($item->is_hidden) {
                $icon = 'times';
            } else {
                $icon = 'eye';
            }

            return '<span class="fa fa-' . $icon . '"></span>';

        })->rawColumns(['action', 'formatted_id', 'featured_icon', 'visibility_icon'])->make();
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
            'parent_id',
            'slug',
            'title',
            'icon',
            'description',
            'is_featured',
            'is_hidden',
            'button_text',
        ]);

        // Upload icon
        if (isset($payload['icon']) && !is_null($payload['icon'])) {
            $payload['icon'] = request()->file('icon')->store(
                'assets/category-icon', 'public'
            );
        }

        $checkbox = [
            'is_featured',
            'is_hidden',
        ];

        foreach ($checkbox as $checkboxKey) {
            if (!isset($payload[$checkboxKey])) {
                $payload[$checkboxKey] = 0;
            }
        }

        return $payload;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['fields'] = Category::mappingFieldForm();

        return view('admin.category.form', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {
        $payload = $this->prepareData($request);
        Category::create($payload);

        return redirect()->route('category.index')->with('status', 'Successfully created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return redirect(route('category.edit', $id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['object'] = Category::findOrFail($id);
        $data['fields'] = Category::mappingFieldForm();

        return view('admin.category.form', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $request, $id)
    {
        $payload = $this->prepareData($request);
        Category::findOrFail($id)->update($payload);

        return redirect()->back()->with('status', 'Successfully updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $object = Category::findOrFail($id);
        $object->delete();

        return redirect()->back()->with('status', 'Successfully deleted.');
    }

    /**
     * Up Position
     * 
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function upPosition($id)
    {
        $object = Category::findOrFail($id);       
        $object->upPosition();

        return redirect()->back();
    }

    /**
     * Down Position
     * 
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function downPosition($id)
    {
        $object = Category::findOrFail($id);       
        $object->downPosition();

        return redirect()->back();
    }
}
