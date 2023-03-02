<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class Aplicacion extends Model
{
    use HasFactory;

    protected $table = 'tb_app_aplicaciones';

    protected $primaryKey = 'aplicacion_id';

    protected $fillable = [
        'nombre,descripcion,tipo_aplicacion,orden,logo,saml,activo,usuario_creador,fecha_creacion,usuario_modificador,fecha_modificacion'
    ];

    // Crear/Actuallizar
    public function crud_aplicaciones(Request $request, $evento) {
        $db = DB::select("exec pr_crud_app_aplicaciones ?,?,?,?,?,?,?,?,?,?,?,?", 
                        [
                            $evento,
                            $request->input('aplicacion_id'),
                            $request->input('nombre'),
                            $request->input('descripcion'),
                            $request->input('tipo_aplicacion'),
                            $request->input('orden'),
                            $request->input('url'),
                            $request->input('logo'),
                            $request->input('saml') == true ? 'S' : 'N',
                            $request->input('activo') == true ? 'S' : 'N',
                            $request->input('usuario_creador'),
                            $request->input('usuario_modificador')
                        ]);
        return $db;
    }

    public function get_aplicaciones(Request $request) {
        $db = DB::select("exec pr_get_app_aplicaciones ?,?",
                        [
                            $request->input('filtro'),
                            $request->input('filtro') + 200
                        ]);
        return $db;
    }

    public function get_aplicaciones_full() {
        $db = DB::select("exec pr_get_app_aplicaciones_full");
        return $db;
    }

    public function get_tipo_aplicaciones() {
        $db = DB::select("exec pr_get_app_tipo_aplicaciones");
        return $db;
    }
}
