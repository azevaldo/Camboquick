<?php

namespace App\Http\Controllers;

use App\Models\Mesa;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MesaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        try {
        $mesas=Mesa::all();
        return view("app.paginas.mesas.index", compact("mesas"));
         } catch (Exception $e) {
            Log::error("Erro : " . $e->getMessage());
            return redirect()->back()->with("error", "Ocorreu um erro: " . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'numero' => 'required|unique:mesas|max:255',
            ]);

            Mesa::create([
                'numero' => $request->numero,
            ]);

            return redirect()->route('mesas.index')->with('success', 'Mesa cadastrada com sucesso!');
        } catch (Exception $e) {
            Log::error('Erro ao cadastrar mesa: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erro ao cadastrar: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $mesa = Mesa::findOrFail($id);
        return view('app.mesas.show', compact('mesa'));
    }

    public function edit($id)
    {
        $mesa = Mesa::findOrFail($id);
        return view('app.mesas.edit', compact('mesa'));
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'numero' => 'required|max:255|unique:mesas,numero,' . $id,
                       'status'=>'required|in:Valido,Arquivado',
            ]);

            $mesa = Mesa::findOrFail($id);
            $mesa->update([
                'numero' => $request->numero,
                'status' => $request->status,
            ]);

            return redirect()->route('mesas.index')->with('success', 'Mesa atualizada com sucesso!');
        } catch (Exception $e) {
            Log::error('Erro ao atualizar mesa: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erro ao atualizar: ' . $e->getMessage());
        }
    }
    public function arquivar($id)
    {
        try {
            $mesa = Mesa::findOrFail($id);
            $mesa->status="Arquivado";
            $mesa->save();
            return redirect()->route("mesas.index")->with("success", "Mesa Arquivada com sucesso!");
        } catch (Exception $e) {
            Log::error("Erro ao arquivar Mesa: " . $e->getMessage());
            return redirect()->back()->with("error", "Ocorreu um erro: " . $e->getMessage());
        }
    }
    public function destroy($id)
    {
        /*
        try {
            $mesa = Mesa::findOrFail($id);
            $mesa->delete();

            return redirect()->route('mesas.index')->with('success', 'Mesa deletada com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao deletar mesa: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erro ao deletar: ' . $e->getMessage());
        }*/
    }
}
