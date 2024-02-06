<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\Request;

class MarcaController extends Controller
{
    protected $marca; 

    //injecao do model

    public function __construct(Marca $marca)
    {
        $this->marca = $marca;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return $this->marca->all();
    }   

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $marca = $this->marca->create($request->all());       
        return $marca;
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
        $marca = $this->marca->find($id);
        return $marca;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $marca = $this->marca->find($id);
        $marca->update($request->all());
        return $marca;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $marca = $this->marca->find($id);
        $marca->delete();
        return ['msg' => 'A marca foi removida com sucesso!'];
    }
}
