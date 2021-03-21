<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Illuminate\Database\QueryException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Exception
     */

      public function render($request, Exception $exception)
    {
        $response = $this->handleException($request, $exception);
        if($response['message'] === 'Unauthenticated.'){
            return response()->json(compact('response'),401);
        }
        return response()->json(compact('response'),$exception->getStatusCode());
    }

    public function handleException($request, Exception $exception)
    {
        if($exception instanceof RouteNotFoundException){
            return $return = array(
                'status' => false,
                'code' => 401,
                'message' => 'Unauthorized access. Invalid bearer token'
            );
        }
        if ($exception instanceof MethodNotAllowedHttpException) {
            return $return = array(
                'status' => false,
                'code' => 405,
                'message' => 'The specified method for the request is invalid'
            );
        }

        if ($exception instanceof NotFoundHttpException) {
            return $return = array(
                'status' => false,
                'code' => 404,
                'message' => 'The specified URL cannot be found'
            );
        }

        // if ($exception instanceof QueryException) {
        //     return $return = array(
        //         'status' => false,
        //         'code' => 400,
        //         'message' => 'Error while executing query'
        //     );
        // }
        if ($exception instanceof HttpException) {
            return $return = array(
                'status' => false,
                'code' => $exception->getStatusCode(),
                'message' => $exception->getMessage()
            );
        }
        if($exception->getMessage() === 'Unauthenticated.'){
            return $return = array(
                'status' => false,
                'code' => 401,
                'message' => $exception->getMessage()
            );
        }

        if (config('app.debug')) {
            dd($exception);
            return parent::render($request, $exception);            
        }

        return $return = array(
            'status' => false,
            'code' => 500,
            'message' => 'Unexpected Exception. Try later'
        );
    }
}
