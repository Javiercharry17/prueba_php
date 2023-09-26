<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TurnoController extends Controller
{
    public function index()
    {
        $turnos = Turno::all();
        return view('llamar-turno', ['turnos' => $turnos]);
    }

    public function llamarTurno(Request $request)
    {
        // Busca el turno por su ID
    $turno = Turno::find($id);

    if (!$turno) {
        // Maneja la situación en la que el turno no existe
        return redirect()->back()->with('error', 'El turno no existe.');
    }

    // Verifica si hay módulos de atención preferenciales sin asignar
    $moduloPreferencial = ModuloAtencion::where('tipo', 'PREFERENCIAL')->where('disponible', true)->first();

    if ($moduloPreferencial && $turno->tipo == 'PREFERENCIAL') {
        // Asigna el turno preferencial a un módulo preferencial
        $turno->tipo = $moduloPreferencial->id;
        $turno->estado = 'LLAMADO'; // Puedes definir un estado para el turno
        $turno->save();
        $moduloPreferencial->disponible = false;
        $moduloPreferencial->save();
    } else {
        // No hay módulos preferenciales disponiblesin asignar o el turno es general
        // Asigna el turno a un módulo general
        $moduloGeneral = ModuloAtencion::where('tipo', 'GENERAL')->where('disponible', true)->first();
        
        if (!$moduloGeneral) {
            // Maneja la situación en la que no hay módulos generales disponibles
            return redirect()->back()->with('error', 'No hay módulos generales disponibles.');
        }

        $turno->modulo_atencion_id = $moduloGeneral->id;
        $turno->estado = 'LLAMADO'; // Puedes definir un estado para el turno
        $turno->save();
        $moduloGeneral->disponible = false;
        $moduloGeneral->save();
    }

    return redirect()->back()->with('success', 'Turno llamado y asignado correctamente.');

        
}
