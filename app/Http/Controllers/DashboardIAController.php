<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DashboardIAController extends Controller
{
    public function index()
    {
        return view('dashboard'); // usa tu dashboard actual
    }

    public function consultarIA(Request $request)
    {
        $juego = $request->input('juego');

        if (!$juego) {
            return back()->withErrors("⚠ Debes escribir el nombre de un juego.")->withInput();
        }

        $apiUrl = "http://127.0.0.1:5001/predict_all";

        // Enviar al API Flask
        $response = Http::post($apiUrl, [
            "game" => $juego
        ]);

        if ($response->failed()) {
            return back()->withErrors(" No se pudo contactar al servidor Flask.")->withInput();
        }

        $data = $response->json();

        if (isset($data["error"])) {
            return back()->withErrors("⚠ ".$data["error"])->withInput();
        }

        return view('dashboard', compact('data', 'juego'));
    }
}
