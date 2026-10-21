<?php

namespace App\Http\Controllers;

use App\Models\CajaQuirurgica;
use App\Models\Cirugia;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Can;

class InicioController extends Controller
{
    //
    //Carga los indicadores del inicio: cajas esterilizadas, cajas almacenadas y cirugías
    public function index() //Pagina inicial
    {
        // Cajas que pasaron por esterilización (según el método), contando cada caja una sola vez
        $cajasPasaronPor = fn (string $metodo) => CajaQuirurgica::where('tipo_esterilizacion', $metodo)
            ->whereHas('historiales', fn ($q) => $q->where('estado_registrado', 'Esterilizada'))
            ->count();
        $cajasOxido = $cajasPasaronPor('Óxido de Etileno');
        $cajasAutoclave = $cajasPasaronPor('Autoclave');

        // Cajas almacenadas actualmente
        $cajasAlmacenadas = CajaQuirurgica::where('estado_actual', 'Almacenada')->count();

        //Cantidad de Cirugías
        $cantCirugias = count(Cirugia::whereYear('fecha_cirugia', date('Y'))->get());

        return view('index', compact('cajasOxido', 'cajasAutoclave', 'cajasAlmacenadas', 'cantCirugias')); //Llama a la vista y le pasa los datos
    }
}
