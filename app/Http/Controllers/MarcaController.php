<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        $marca = $this->marca->with('modelos')->get();
        return response()->json($marca, 200);
    }   

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate($this->marca->rules(),$this->marca->feedback());

        $imagem = $request->file('imagem');
        $imagem_urn = $imagem->store('imagens','public');        

        $marca = $this->marca->create([
            'nome' => $request->nome,
            'imagem' => $imagem_urn
        ]);  
        /**
         * Ou podemos fazer:
         * $marca->nome = $request->nome;
         * $marca->imagem = $imagem_urn;
         * 
         */
        $marca->save();     
        return response()->json($marca, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
        $marca = $this->marca->with('modelos')->find($id);
        if($marca === null){
            //return ['erro' => 'Recurso pesquisado não existe.' ];
            return response()->json(['erro' => 'Recurso pesquisado não existe.'],404);
        }
        return response()->json($marca,200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $marca = $this->marca->find($id);

        if($marca === null){

            return response()->json(['erro' => 'Impossível realizar a atualização. Recurso solicitado não existe.'],404);      
        }

        if ($request->method() === 'PATCH' ) {
            
            $regrasDinamicas = array();

            foreach($marca->rules() as $input => $regra){
                if(array_key_exists($input, $request->all())){
                    $regrasDinamicas[$input]= $regra;
                }
            }

            $request->validate($regrasDinamicas, $marca->feedback());

        }else{

            $request->validate($marca->rules(), $marca->feedback());
        }

        $marca->fill($request->all());

        //remove o arquivo antigo caso um novo arquivo tenha sido enviado no request e add novo
        if($request->file('imagem')){
            Storage::disk('public')->delete($marca->imagem);
            $imagem = $request->file('imagem');
            $imagem_urn = $imagem->store('imagens','public');       
            $marca->imagem = $imagem_urn;
        }

        $marca->save();
        
        return response()->json($marca,200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $marca = $this->marca->find($id);
        if($marca === null){
            return response()->json(['erro' => 'Impossível realizar a exclusão. Recurso solicitado não existe.'],404);
        }
        
        Storage::disk('public')->delete($marca->imagem);      

        $marca->delete();
        return response()->json(['msg' => 'A marca foi removida com sucesso!'],200);
    }
}
