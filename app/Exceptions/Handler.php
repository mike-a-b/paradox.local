<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

use Illuminate\Auth\AuthenticationException;
use Auth;
use Illuminate\Session\TokenMismatchException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        //dd($request, $exception);
        $redirectTo = route('login');
        if (strpos($request->url(), 'admin') !== false) {
            $redirectTo = route('admin.login');
        }

        // if (in_array('admin', $exception->guards())) {
        //     return $request->expectsJson()
        //         ? response()->json([
        //               'message' => $exception->getMessage()
        //         ], 401)
        //         : redirect()->guest(route('admin.login'));
        // }
    
        return $request->expectsJson()
            ? response()->json([
                  'message' => $exception->getMessage()
            ], 401)
            : redirect()->guest($redirectTo);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $exception)
    {        
        if ($this->isHttpException($exception)) {
            $redirectTo = redirect()->route('home');
            if (strpos($request->url(), 'admin') !== false) {
                $redirectTo = redirect()->route('admin.asset-pools.index');
            }  
            switch ($exception->getStatusCode()) {
                // not found
                case 404:
                    return $redirectTo;
                break;

                default:
                    //return $this->renderHttpException($e);
                    return parent::render($request, $exception);
                break;
            }
        } elseif ($exception instanceof TokenMismatchException) {  
            $redirectTo = redirect()->route('login');
            if (strpos($request->url(), 'admin') !== false) {
                $redirectTo = redirect()->route('admin.login');
            }                      
            //return redirect()->route('login')->withInput($request->all())->with('page_expired', 'Page Session Expired');
            return $redirectTo->withInput($request->all())->withErrors(['page_expired' => 'Page Session Expired']);
        } else {
            return parent::render($request, $exception);
        }
    }
}
