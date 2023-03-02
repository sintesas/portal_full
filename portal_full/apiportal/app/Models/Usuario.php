<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'tb_app_usuarios';

    protected $primaryKey = 'usuario_id';

    protected $fillable = [
        'usuario,activo,usuario_creador,fecha_creacion,usuario_modificador,fecha_modificacion'
    ];

    public function crud_usuarios(Request $request, $evento) {
        $db = DB::select("exec pr_crud_app_usuarios ?,?,?,?,?,?,?,?,?",
                        [
                            $evento,
                            $request->input('usuario_id'),
                            $request->input('usuario'),
                            $request->input('nombres'),
                            $request->input('apellidos'),
                            $request->input('email'),
                            $request->input('activo') == true ? 'S' : 'N',
                            $request->input('usuario_creador'),
                            $request->input('usuario_modificador')
                        ]);
        return $db;
    }

    public function crear_usuario(Usuario $user) {
        $db = DB::insert("insert into tb_app_usuarios (usuario,nombres,apellidos,email,activo,usuario_creador) values (?,?,?,?,?,?)", 
                        [
                            $user->usuario,
                            $user->nombres,
                            $user->apellidos,
                            $user->email,
                            $user->activo,
                            'admin'
                        ]);
        return $db;
    }

    public function checkUsuario($usuario) {
        $result = DB::select("exec pr_check_usuario ?", array($usuario));
        if (count($result) > 0)
            return true;
        else return false;
    }

    public function getLoginUsuario($usuario) {
        $result = DB::select("exec pr_get_login_usuario ?", array($usuario));

        return $result;
    }
}
