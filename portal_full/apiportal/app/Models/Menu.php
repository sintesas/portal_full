<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class Menu extends Model
{
    use HasFactory;

    protected $table = 'tb_app_menu';

    protected $primaryKey = 'menu_id';

    protected $fillable = [
        'menu_nombre,tipo,menu_padre_id,icono,tooltip,url,activo'
    ];

    public function crud_menus(Request $request, $evento) {
        $db = DB::select("exec pr_crud_app_menu ?,?,?,?,?,?,?,?,?,?,?",
                        [
                            $evento,
                            $request->input('menu_id'),
                            $request->input('nombre_menu'),
                            $request->input('tipo_menu_id'),
                            $request->input('menu_padre_id'),
                            $request->input('icono'),
                            $request->input('tooltip'),
                            $request->input('url'),
                            $request->input('activo') == true ? 'S' : 'N',
                            $request->input('usuario_creador'),
                            $request->input('usuario_modificador')
                        ]);
        return $db;
    }

    public function get_menus(Request $request)
    {
        $db = DB::select("exec pr_get_app_menu ?,?", 
                        [
                            $request->input('filtro'),
                            $request->input('filtro') + 200
                        ]);

        return $db;
    }

    public function get_menu_id($menus) {
        $data = array();
        foreach($menus as $row) {
            $tmp = array();
            $tmp['menu_id'] = $row->menu_id;
            $tmp['titulo'] = $row->nombre_menu;
            $tmp['tipo_menu_id'] = $row->tipo_menu_id;
            $tmp['tipo'] = $row->tipo;
            $tmp['menu_padre_id'] = $row->menu_padre_id;
            $tmp['icono'] = $row->icono;
            $tmp['tooltip'] = $row->tooltip;
            $tmp['url'] = $row->url;
            $tmp['submenus'] = array();

            array_push($data, $tmp);
        }

        $result = $this->makeNested($data);

        return $result;
    }

    public function makeNested($data, $padre_id = 0) {
        $tree = array();
        foreach ($data as $d) {
            if ($d['menu_padre_id'] == $padre_id) {
                $submenus = $this->makeNested($data, $d['menu_id']);
                if (!empty($submenus)) {
                    $d['submenus'] = $submenus;
                }
                $tree[] = $d;
            }
        }
        return $tree;
    }
}
