<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Socialite;
use Auth;
use App\User;
use Illuminate\Http\Request;
use Session;

use App\Core\Entities\Admin\mhr;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/admin/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */


    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    protected function sendLoginResponse(Request $request)
    {

        $request->session()->regenerate();
        $previous_session = Auth::User()->session_id;
        if ($previous_session) {
            \Session::getHandler()->destroy($previous_session);
        }

        Auth::user()->session_id = \Session::getId();
        Auth::user()->save();

        $this->clearLoginAttempts($request);

        return $this->authenticated($request, $this->guard()->user())
            ?: redirect()->intended($this->redirectPath());
    }

    public function username()
    {
        return 'name';
    }

    protected function validateLogin(Request $request)
    {
        $this->validate($request, ['name' => 'required|exists:users,' . 'name' . ',estado,A', 'password' => 'required',]);
    }

    public function registrarse()
    {
        return view('auth.register');
    }
    public function registrarsestore(request $data)
    {
        $consulta = User::create([
            'name' => $data->name,
            'email' => $data->email,
            'nombreCompleto' => $data->nombre,
            'password' => bcrypt($data->password),
        ]);
        $consulta2 = new mhr();
        $consulta2->role_id = 6;
        $consulta2->model_id = $consulta->id;
        $consulta2->model_type = 'App\User';
        $consulta2->save();
        return redirect()->route('auth.login');
    }

    /**
     * TODO:
     * Consulta a la API de seguridad data del usuario: retornando status: 200 OK, ó, 401 error...
     *
     */
    private function validacionUsuario(request $request)
    {
        // * recoge el valor seteado en la variable de ambiente...
        $url = config("app.API_HOST_SEGURIDAD") . config("app.API_URL");
        // * reemplazamos la cadena con los parametros...
        $url = str_replace(config("app.API_REMPLAZO_USUARIO"), $request->all()["name"], $url);
        $url = str_replace(config("app.API_REMPLAZO_PASSWORD"), $request->all()["password"], $url);
        // * inicia curl...
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // * ejecuta curl...
        $response = curl_exec($ch);
        // * decodifica el json...
        $response = json_decode($response);
        // * cierra curl...
        curl_close($ch);
        // * retorna resultado...
        return $response;
    }

    private function errorLogin($result)
    {
        // * redireccion login...
        return redirect()->back()->withErrors(['error' => $result->message]);
    }

    protected function agregarRolBase($usuario)
    {
        $user = User::find($usuario);
        //   $user->syncRoles(['DOCTOR']);
        return true;
    }

    private function registraUsuario($usuario, request $request)
    {
        try {
            //code...
            // * registra el usuario...
            $user = new User();
            //  * seteo propiedades...
            $user->name = $request->name;
            $user->email = $usuario->correo_institucional;
            $user->password = bcrypt($request->password);
            $user->nombreCompleto = $usuario->apellidos_nombres;
            $user->session_id = \Session::getId();
            // * registra el usuario...
            $user->save();
            // * crea rol base...
            //$this->agregarRolBase($user->id);
            // * retorna el objeto...
            return $this->verificaUsuario($request);
            // * retorna el objeto...
        } catch (\Throwable $th) {
            //throw $th;
            throw $th;
        }
    }

    private function verificaUsuario($request)
    {
        // * busca le usuario...
        return User::where($this->username(), $request->name)->where('estado', 'A')->first();
    }

    private function registraIngreso($usuario, Request $request)
    {
        try {
            //code...
            // * verifica si está registrado...
            $user = $this->verificaUsuario($request);
            // * verifica si existe el usuario...
            // * si no existe registra el usuario...
            if ($user == null) $user = $this->registraUsuario($usuario, $request);
            // * agrega el guard...
            $this->guard()->login($user, true);
        } catch (\Throwable $th) {
            //throw $th;
            throw $th;
        }
    }

    public function login(request $request)
    {
        try {
            //code...
            // * consulta API LDAP...
            $result = $this->validacionUsuario($request);
            // * validando resultados...
            // * 401 error, retorna el error...
            if (is_null($result)) return redirect()->back()->withErrors(['error' => 'ERROR DE RED']);
            if ($result->status == 401) return $this->errorLogin($result);
            // * todo OK registramos el usuario...
            $this->registraIngreso($result->usuario, $request);
            // * redirecciona al home...
            return $this->sendLoginResponse($request);
        } catch (\Throwable $th) {
            // throw $th;
            throw $th;
        }
    }
}
