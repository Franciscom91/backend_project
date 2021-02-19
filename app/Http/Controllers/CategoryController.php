<?php

namespace App\Http\Controllers;

use App\Post;
use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['categories'] = Category::paginate(5);
        return view("category.index", $data);
    }

    public function list()
    {
        $data = Category::all();
        return response()->json(["categories" => $data], 200);
    }

    public function search(Request $request)
    {
        //$data = $request->all();
        $data = $request->input('search');
        $query = Category::select()
            ->where('name', 'like', "%$data%")
            ->get();
        return view("category.index")->with(["categories" => $query]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        return view("category.create")->with(["categories" => $categories]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //$data = $request->all();
        $data = $request->except('_token');
        Category::insert($data);
        Session::flash('alert-success', 'Se ha Creado con Éxito!');
        return redirect()->route("category.index");
    }

    public function save(Request $request)
    {
        Category::insert($request);
        return response()->json("La información se guardó con éxito", 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Category::findOrFail($id);
        return view("category.edit")->with(["category" => $data]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->except('_token', '_method');
        Category::where('id', '=', $id)->update($data);
        Session::flash('alert-success', 'Se ha Modificado con Éxito!');
        return redirect()->route("category.index");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Category::destroy($id);
        Session::flash('alert-success', 'Se ha Eliminado con Éxito!');
        return redirect()->route("category.index");
    }
}
