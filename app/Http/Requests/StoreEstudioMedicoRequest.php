<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreEstudioMedicoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::user()->hasAccess('estudios_medicos');
    }

    public function rules(): array
    {
        return [
            // Paciente y médico solicitante
            'paciente_id'           => ['required', 'integer', 'exists:pacientes,id'],
            'medico_solicitante_id' => ['required', 'integer', 'exists:empleados,id'],

            // Ámbito: Interno (I) o Ambulatorio (A) -> viene del toggle switch
            'ia'                    => ['nullable', 'string', 'in:I,A'],

            // Modalidad (Rayos / Tomografía) -> restringida a especialidades marcadas como modalidad de imagen
            'especialidad_id'       => [
                'required', 'integer',
                Rule::exists('especialidads', 'id')->where(fn ($q) => $q->where('es_modalidad_imagen', true)),
            ],

            // Estudio real -> tiene que pertenecer a la modalidad seleccionada arriba
            'estudio_id'            => [
                'required', 'integer',
                Rule::exists('estudios', 'id')->where(fn ($q) => $q->where('especialidad_id', $this->input('especialidad_id'))),
            ],

            // Cantidad de regiones vistas (numeral)
            'regiones'              => ['required', 'integer', 'min:1', 'max:20'],

            'fecha'                 => ['required', 'date', 'before_or_equal:today'],

            // Insumos y medios de contraste (contadores +/-)
            'cont_50ml'             => ['required', 'integer', 'min:0'],
            'cont_100ml'            => ['required', 'integer', 'min:0'],
            'jeringa_prellenada'    => ['required', 'integer', 'min:0'],
            'descartables'          => ['nullable', 'string', 'max:255'],
            'otros_agujas'          => ['nullable', 'string', 'max:255'],

            // Resultado / informe
            'resultado'             => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'estudio_id.exists' => 'El estudio seleccionado no corresponde a la modalidad elegida.',
            'especialidad_id.exists' => 'La modalidad seleccionada no es válida.',
        ];
    }
}
