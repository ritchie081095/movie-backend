<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Movie;
use Illuminate\Http\Response;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         $this->field_validate($request);

        $item = Movie::updateOrCreate(
        ['name' => $request->name],
        ['name' => $request->name, 'rating' => $request->rating , 'duration' => $request->duration]
        );
        if($item->id){
             return 1;
        }
     
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function field_validate($request){
        return $this->validate($request,
            [
                'name'                      => 'required',
            ]
        );
    }

    public function searchMovies(Request $request){
        $query = Movie::select('*')->orderBy('id', 'desc');
         if($request->search){
            $query->where('name','LIKE','%'.$request->search.'%');
         }
         $query = $query->get();

         return $query;
    }
}
