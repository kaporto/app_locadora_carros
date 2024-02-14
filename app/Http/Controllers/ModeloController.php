<?php

namespace App\Http\Controllers;

use App\Models\Modelo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ModeloController extends Controller
{

    protected $modelo;

    public function __construct(Modelo $modelo)
    {
        $this->modelo = $modelo;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $modelos = array();

        if($request->has('atributos_marca')){
            $atributos_marca = $request->atributos_marca;
            $modelos = $this->modelo->with('marca:id,'.$atributos_marca);
        }else{
            $modelos = $this->modelo->with('marca');
        }


        if($request->has('filtro')){

            $filtros = explode(';',$request->filtro);
            foreach($filtros as $key => $condicao){

                $c = explode(':',$condicao);
                $modelos = $modelos->where($c[0],$c[1],$c[2]);
            }
            
        }

        if($request->has('atributos')){
            $atributos = $request->atributos;
            $modelos = $modelos->selectRaw($atributos)->get();
        }else{
            $modelos = $modelos->get();
        }
        
        return response()->json($modelos, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate($this->modelo->rules());

        $imagem = $request->file('imagem');
        $imagem_urn = $imagem->store('imagens','public');        

        $modelo = $this->modelo->create([
            'marca_id' => $request->marca_id,
            'nome' => $request->nome,
            'imagem' => $imagem_urn,
            'numero_portas' => $request->numero_portas,
            'lugares' => $request->lugares,
            'air_bag' => $request->air_bag,
            'abs' => $request->abs
        ]);  
       
        $modelo->save();     
        return response()->json($modelo, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
        $modelo = $this->modelo->with('marca')->find($id);
        if($modelo === null){
            
            return response()->json(['erro' => 'Recurso pesquisado não existe.'],404);
        }
        return response()->json($modelo,200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
        $modelo = $this->modelo->find($id);

        if($modelo === null){

            return response()->json(['erro' => 'Impossível realizar a atualização. Recurso solicitado não existe.'],404);      
        }

        if ($request->method() === 'PATCH' ) {
            
            $regrasDinamicas = array();

            foreach($modelo->rules() as $input => $regra){
                if(array_key_exists($input, $request->all())){
                    $regrasDinamicas[$input]= $regra;
                }
            }

            $request->validate($regrasDinamicas);

        }else{

            $request->validate($modelo->rules());
        }

        $modelo->fill($request->all());


        //remove o arquivo antigo caso um novo arquivo tenha sido enviado no request e add novo
        if($request->file('imagem')){
            Storage::disk('public')->delete($modelo->imagem);
            $imagem = $request->file('imagem');
            $imagem_urn = $imagem->store('imagens','public');
            $modelo->imagem = $imagem_urn;
        }      

        $modelo->save();
        
        return response()->json($modelo,200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        $modelo = $this->modelo->find($id);
        if($modelo === null){
            return response()->json(['erro' => 'Impossível realizar a exclusão. Recurso solicitado não existe.'],404);
        }
        
        Storage::disk('public')->delete($modelo->imagem);      

        $modelo->delete();
        return response()->json(['msg' => 'O modelo foi removido com sucesso!'],200);
    }
}
