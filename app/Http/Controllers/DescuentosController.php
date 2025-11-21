<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DescuentosController extends Controller
{
    public function index()
    {
        return view('descuentos.index');
    }

    public function consultar(Request $request)
    {
        $juego = $request->input('juego');

        if (!$juego) {
            return back()->withErrors("⚠ Debes escribir un nombre de juego.");
        }

        // URL del servidor Python Flask
        $apiUrl = "http://127.0.0.1:5001/predict";

        // Enviar juego al API de Python
        $response = Http::post($apiUrl, [
            "game" => $juego
        ]);

        if ($response->failed()) {
            return back()->withErrors("❌ Error al conectar con Python.");
        }

        $data = $response->json();

        if (isset($data["error"])) {
            return back()->withErrors($data["error"]);
        }

        return view('descuentos.resultado', compact('data'));
    }
}
