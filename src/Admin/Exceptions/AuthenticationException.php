<?php

namespace QuarkCloudIO\QuarkAdmin\Exceptions;

use Illuminate\Support\Facades\Route;
use Illuminate\Auth\AuthenticationException as BaseAuthenticationException;

class AuthenticationException extends BaseAuthenticationException
{
    /**
     * Render the exception.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        return $request->expectsJson()
                    ? response()->json(['message' => $this->getMessage()], 401)
                    : redirect()->guest($this->location());
    }

    /**
     * Determine the location the user should be redirected to.
     *
     * @return string
     */
    protected function location()
    {
        return '/admin';
    }
}
