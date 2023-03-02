<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Aplicacion;

class AplicacionController extends Controller
{
    public function getAplicaciones(Request $request) {
        $model = new Aplicacion();

        $datos = $model->get_aplicaciones($request);

        $response = json_encode(array('result' => $datos, 'tipo' => 0), JSON_NUMERIC_CHECK);
        $response = json_decode($response);

        return response()->json($response, 200);
    }

    public function getAplicacionesFull() {
        $model = new Aplicacion();

        $datos = $model->get_aplicaciones_full();

        $response = json_encode(array('result' => $datos, 'tipo' => 0), JSON_NUMERIC_CHECK);
        $response = json_decode($response);

        return response()->json($response, 200);
    }

    public function crearAplicaciones(Request $request) {
        $model = new Aplicacion();

        try {
            $db = $model->crud_aplicaciones($request, 'C');

            if ($db) {
                $id = $db[0]->id;

                $response = json_encode(array('mensaje' => 'Fue creado exitosamente.', 'tipo' => 0, 'id' => $id), JSON_NUMERIC_CHECK);
                $response = json_decode($response);

                return response()->json($response, 200);
            }
        }
        catch (Exception $e) {
            return response()->json(array('tipo' => -1, 'mensaje' => $e));
        }
    }

    public function actualizarAplicaciones(Request $request) {
        $model = new Aplicacion();

        try {
            $db = $model->crud_aplicaciones($request, 'U');

            if ($db) {
                $response = json_encode(array('mensaje' => 'Fue actualizado exitosamente.', 'tipo' => 0), JSON_NUMERIC_CHECK);
                $response = json_decode($response);

                return response()->json($response, 200);
            }
        }
        catch (Exception $e) {
            return response()->json(array('tipo' => -1, 'mensaje' => $e));
        }
    }

    public function getTipoAplicaciones() {
        $model = new Aplicacion();

        $datos = $model->get_tipo_aplicaciones();

        $response = json_encode(array('result' => $datos, 'tipo' => 0), JSON_NUMERIC_CHECK);
        $response = json_decode($response);

        return response()->json($response, 200);
    }
}
