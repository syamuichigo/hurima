<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LogoutResponse as LogoutResponseContract;

class LogoutResponse implements LogoutResponseContract
{
    /**
     * Redirect users to the login page after logout.
     */
    public function toResponse($request)
    {
        return redirect()->route('login');
    }
}

