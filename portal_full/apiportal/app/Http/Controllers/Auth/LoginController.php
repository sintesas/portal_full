<?php

namespace App\Http\Controllers\Auth;

use Aacotroneo\Saml2\Saml2Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Menu;
use App\Models\Usuario;
use App\Models\UsuarioMenu;

class LoginController extends Controller
{
    //
    public function saml() {
        // $url = "http://localhost:4201/";
        $url = 'https://apps.fac.mil.co/';

        if (\Auth::guest()) {
            try {
                $saml2Auth = new Saml2Auth(Saml2Auth::loadOneLoginAuthFromIpdConfig('portal'));
                return $saml2Auth->login(session()->pull('url.intended'));
            }
            catch (\Exception $e) {
                return abort(503);
            }
        }
        else {
            $user = \Auth::user();
            $user = base64_encode($user->usuario);
            $query = http_build_query([
                'id' => $user,
                'type' => 'granted'
            ]);
            return redirect($url.'/saml/?'. $query);
        }
    }

    public function login(Request $request) {
        $p_usuario = $request->get('usuario');
        $p_password = $request->get('password');
        
        $m_usuario = new Usuario();

        try {
            $users = DB::table('tb_app_usuarios')->where('usuario', $p_usuario)->get();

            if (!$users->isEmpty()) {
                $usuario = $m_usuario->getLoginUsuario($p_usuario);

                $m_menu = new Menu();
                $m_usuariomenu = new UsuarioMenu();

                $data = array();
                foreach ($usuario as $row) {
                    $tmp = array();
                    $tmp['usuario_id'] = $row->usuario_id;
                    $tmp['usuario'] = $row->usuario;
                    $tmp['nombre_completo'] = $row->nombre_completo;
                    $tmp['email'] = $row->email;
                    $tmp['menus'] = $m_menu->get_menu_id($m_usuariomenu->getUsuarioMenu($row->usuario_id));

                    array_push($data, $tmp);
                }

                $response = json_encode(array('result' => $data), JSON_NUMERIC_CHECK);
                $response = json_decode($response);
                return response()->json(array('user' => $response, 'tipo' => 0));
            }
        }
        catch (\PDOException $e) {
            if ($e->getCode() == "08001") {
                return response()->json(array('tipo' => -1, 'mensaje' => "No se puede conectar a la base de datos. Contactar al administrador."));
            }
            else {
                return response()->json(array('tipo' => -1, 'mensaje' => "El usuario no existe, vuelve a ingresar."));
            }
        }
    }

    public function logout() {
        // $url = "http://localhost:4201";
        $url = 'https://apps.fac.mil.co';

        // recover sessionIndex and nameId from session
        $nameId = session('nameId');
        $sessionIndex = session('sessionIndex');

        try {
            $saml2Auth = new Saml2Auth(Saml2Auth::loadOneLoginAuthFromIpdConfig('portal'));
            $saml2Auth->logout($url, $nameId, $sessionIndex);
        }
        catch (\Exception $e) {
            abort(500);
        }
    }
}
