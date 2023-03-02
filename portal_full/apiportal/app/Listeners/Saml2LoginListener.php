<?php

namespace App\Listeners;

use Aacotroneo\Saml2\Events\Saml2LoginEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class Saml2LoginListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\Saml2LoginEvent  $event
     * @return void
     */
    public function handle(Saml2LoginEvent $event)
    {
        $saml2User = $event->getSaml2User();
        $samlAttributes = $saml2User->getAttributes();
        $userData = array(
            'id' => $saml2User->getUserId(),
            'email' => $samlAttributes['Email'][0],
            'firstname' => $samlAttributes['Firstname'][0],
            'lastname' => $samlAttributes['Lastname'][0],
            'assertion' => $saml2User->getRawSamlAssertion(),
            'sessionIndex' => $saml2User->getSessionIndex(),
            'nameId' => $saml2User->getNameId()
        );
        
        // Verificar si el usuario ya existe y obtener el usuario
        $user = \App\Models\Usuario::where('usuario', $userData['id'])->first();

        // Si el usuario no existe, crea nuevo usuario
        if ($user == null) {
            $user = new \App\Models\Usuario;
            $user->usuario = $userData['id'] == null ? null : $userData['id'];
            $user->nombres = $userData['firstname'] == null ? null : $userData['firstname'];
            $user->apellidos = $userData['lastname'] == null ? null : $userData['lastname'];
            $user->email = $userData['email'] == null ? null : $userData['email'];
            $user->activo = 'S';
            $user->crear_usuario($user);            
        }

        session(['nameId' => $userData['nameId']]);
        session(['sessionIndex' => $userData['sessionIndex']]);        

        // login usuario
        \Auth::login($user);
    }
}
