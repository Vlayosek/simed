<?php

namespace App\Http\Controllers\TalentoHumano;

use App\Core\Entities\modules\HistoriasMedicas\AtencionMedica;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Core\Entities\modules\HistoriasMedicas\Discapacidad;
use App\Core\Entities\modules\HistoriasMedicas\ExamenGeneralEspecifico;
use App\Core\Entities\TalentoHumano\Distributivo\Persona;
use App\Http\Controllers\HistoriasMedicas\RegistroController;
use DB;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\CollectionDataTable;
use App\Http\Controllers\Ajax\SelectController;

class RepositorioController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function descargarPdf64(Request $request)
    {
        try {
            $array_response['status'] = "200";
            $imagen = null;
            $sc = new SelectController();


            if ($request->tabla == 'DISCAPACIDAD') {
                $cqlConsulta = Discapacidad::where('id', $request->id)->first();
                $imagen = is_null($cqlConsulta) ? null : $cqlConsulta->imagen;
                $extension = is_null($cqlConsulta) ? null : $cqlConsulta->extension;
                $array_response['name'] = $cqlConsulta->id . '_discapacidad_' . $cqlConsulta->codigo . '.' . $extension;
                $url = '/HISTORIA_MEDICA/DISCAPACIDAD/' . $array_response['name'];
            }

            if ($request->tabla == 'ENFERMEDAD') {
                $cqlConsulta = ExamenGeneralEspecifico::where('id', $request->id)->first();
                $imagen = is_null($cqlConsulta) ? null : $cqlConsulta->imagen;
                $extension = is_null($cqlConsulta) ? null : $cqlConsulta->extension;
                $array_response['name'] = $cqlConsulta->id.'_enfermedad_' . $cqlConsulta->codigo . '.' . $extension;
                $url = '/HISTORIA_MEDICA/ENFERMEDAD/' . $array_response['name'];
            }

            if (!is_null($imagen)) {
                $array_response['datos'] = '/storage/SIMED' .$url;
                $grabadoDeBase64 = $sc->base64_to_imagen($imagen, $url);
                if (!$grabadoDeBase64) throw new \Exception("No se puede descargar");
            } else throw new \Exception("No se puede descargar");
        } catch (\Exception $e) {
            $array_response['status'] = 404;
            $array_response['datos'] = $e->getMessage();
        }
        return response()->json($array_response, 200);
    }

    public function editarPersonaDiscapacidad(Request $request)
    {
        try {
            $array_response['status'] = "200";
            $array_response['datos'] = Discapacidad::where('id', $request->id)->first();
        } catch (\Exception $e) {
            $array_response['status'] = 404;
            $array_response['datos'] = null;
        }
        return response()->json($array_response, 200);
    }

    public function eliminarPersonaDiscapacidad(Request $request)
    {
        try {
            DB::connection('pgsql_presidencia')->beginTransaction();
            Discapacidad::where('id', $request->id)->where('eliminado', false)->update(['eliminado' => true, 'usuario_modifica' => Auth::user()->name, 'fecha_modifica' => date("Y-m-d H:i:s")]);

            DB::connection('pgsql_presidencia')->commit();
            $array_response['status'] = "200";
            $array_response['datos'] = "Grabado exitosamente";
        } catch (\Exception $e) {
            DB::connection('pgsql_presidencia')->rollBack();
            $array_response['status'] = 404;
            $array_response['datos'] = $e->getMessage();
        }
        return response()->json($array_response, 200);
    }

    public function guardarDiscapacidad(Request $request)
    {
        $extension = null;
        if (!is_null($request->imagen))
            $extension = explode('/', mime_content_type($request->imagen))[1];

        try {
            DB::connection('pgsql_presidencia')->beginTransaction();

            if (!is_null($request->imagen) || $request->imagen != false) {
                $validaPdf = substr($request->imagen, 0, 20);
                $validaImagen = substr($request->imagen, 0, 10);

                if ($validaPdf != "data:application/pdf" && $validaImagen != "data:image") {
                    $array_response['status'] = 300;
                    $array_response['datos'] = 'Error en el tipo de imagen ingresado';
                    return response()->json($array_response, 200);
                }
            }

            $hoy = date("Y-m-d H:i:s");
            $persona_id = Persona::where('identificacion', $request->identificacion)->first();
            $persona_id = $persona_id->id;
            if ($request->id == '0') {

                $cql = new Discapacidad();
                $cql->fecha_inserta = $hoy;
                $cql->usuario_inserta = Auth::user()->name;
            } else {
                $cql = Discapacidad::find($request->id);
                $cql->fecha_modifica = $hoy;
                $cql->usuario_modifica = Auth::user()->name;
            }
            $cql->codigo = $request->codigo;
            $cql->persona_id = $persona_id;
            $cql->nombre = $request->nombre;
            $cql->porcentaje = $request->porcentaje;
            $cql->numero_carnet = $request->numero_carnet;
            $cql->eliminado = false;
            $cql->imagen = $request->imagen;
            $cql->extension =  $extension;
            $cql->save();

            DB::connection('pgsql_presidencia')->commit();
            $array_response['status'] = "200";
            $array_response['datos'] = "Grabado exitosamente";
            $array_response['persona_id'] =  $cql->persona_id;
        } catch (\Exception $e) {
            DB::connection('pgsql_presidencia')->rollBack();
            $array_response['status'] = 404;
            $array_response['datos'] = $e->getMessage();
        }
        return response()->json($array_response, 200);
    }

    public function datatableDiscapacidad($identificacion, $id)
    {
        $persona_id = Persona::where('identificacion', $identificacion)->first();
        $persona_id = is_null($persona_id) ? 0 : $persona_id->id;
        $data = Discapacidad::select(
            'nombre',
            'numero_carnet',
            'porcentaje',
            'id',
            'codigo'
        );
        if ($id != '0') $data = $data->whereIn('codigo', AtencionMedica::select('codigo')->where('identificacion', $identificacion)->where('id', '<=', $id)->pluck('codigo'));
        $data = $data->where('persona_id', $persona_id)
            ->where('eliminado', false)
            ->orderBy('id', 'desc')->get();
        return (new CollectionDataTable($data))
            ->addIndexColumn()
            ->addColumn('', function ($row) {
                if (!(new RegistroController())->verificarEdicionDatos($row->codigo)) return 'Consulta /' . $row->codigo;

                $btn = ' <table style="width:100%;border:0px">';
                $btn .= '<tr><td style="padding: 2px;border:0px"><a title="Editar" class="btn btn-block btn-outline-primary  btn-xs"  href="#modal-container-discapacidad" role="button" data-toggle="modal" onclick="app_discapacidad.editarPersonaDiscapacidad(\'' . $row->id . '\')" ><i class="fa fa-cog"></i>&nbsp;Editar</a></td></tr>';
                $btn .= '<tr><td style="padding: 2px;border:0px"><button title="Eliminar" class="btn btn-block btn-outline-danger  btn-xs"  onclick="app_discapacidad.eliminarPersonaDiscapacidad(\'' . $row->id . '\')"><i class="fa fa-trash"></i>&nbsp;Eliminar</button></td></tr>';
                $btn .= '</table>';
                return $btn;
            })
            ->rawColumns([''])
            ->toJson();
    }

    public function datatableHabitos($identificacion)
    {
        $persona_id = Persona::where('identificacion', $identificacion)->first();
        $persona_id = is_null($persona_id) ? 0 : $persona_id->id;
        $data = Discapacidad::select(
            'nombre',
            'numero_carnet',
            'porcentaje',
            'id'
        )
            ->where('persona_id', $persona_id)
            ->whereNotIn('eliminado', [true])
            ->orderBy('id', 'desc')->get();
        return (new CollectionDataTable($data))
            ->addIndexColumn()
            ->addColumn('', function ($row) {

                $btn = ' <table ><tr>';
                $btn .= '<td style="padding: 2px"><a title="Editar" class="btn btn-outline-primary  btn-xs"  href="#modal-container-discapacidad" role="button" class="btn"
                data-toggle="modal" onclick="app_discapacidad.editarPersonaDiscapacidad(\'' . $row->id . '\')" ><i class="fa fa-cog"></i>&nbsp;Editar</a></td>';
                $btn .= '<td style="padding:2px"><button title="Eliminar" class="btn btn-outline-danger  btn-xs"  onclick="app_discapacidad.eliminarPersonaDiscapacidad(\'' . $row->id . '\')"><i class="fa fa-trash"></i>&nbsp;Eliminar</button></td>';
                $btn .= ' </tr></table>';
                return $btn;
            })
            ->rawColumns([''])
            ->toJson();
    }
}
