<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Cookie;

class CreateFreshApiToken
{
    /**
     * The authentication guard.
     *
     * @var string
     */
    protected $guard;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  string|null $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $this->guard = $guard;
        $response = $next($request);
        if (Auth::token()) {
            $response->withCookie(new Cookie(config('web.cookie'), Auth::token()));
        }
        return $response;
    }

    /**
     * Determine if the given request should receive a fresh token.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Illuminate\Http\Response $response
     * @return bool
     */
    protected function shouldReceiveFreshToken($request, $response)
    {
        return $this->requestShouldReceiveFreshToken($request) &&
            $this->responseShouldReceiveFreshToken($response);
    }

    /**
     * Determine if the request should receive a fresh token.
     *
     * @param  \Illuminate\Http\Request $request
     * @return bool
     */
    protected function requestShouldReceiveFreshToken($request)
    {
        return $request->isMethod('GET') && $request->user($this->guard);
    }

    /**
     * Determine if the response should receive a fresh token.
     *
     * @param  \Illuminate\Http\Response $response
     * @return bool
     */
    protected function responseShouldReceiveFreshToken($response)
    {
        return ($response instanceof Response ||
                $response instanceof JsonResponse) &&
            !$this->alreadyContainsToken($response);
    }

    /**
     * Determine if the given response already contains an API token.
     *
     * This avoids us overwriting a just "refreshed" token.
     *
     * @param  \Illuminate\Http\Response $response
     * @return bool
     */
    protected function alreadyContainsToken($response)
    {
        foreach ($response->headers->getCookies() as $cookie) {
            if ($cookie->getName() === config('api.cookie')) {
                return true;
            }
        }
        return false;
    }
}
