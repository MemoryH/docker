<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Basesvr\SimpleCommon\Utils\ReturnJson;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [

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
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
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
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($request->expectsJson()) {
            \Log::error($exception);
            if ($exception instanceof ValidationException) {
                return $this->validationHandle($exception);
            }
            if ($exception instanceof MethodNotAllowedHttpException) {
                $method = $exception->getHeaders();
                return ReturnJson::fail('请使用' . $method['Allow'] . '方式请求此接口', 405);
            }
            if ($exception instanceof AuthenticationException) {
                return $this->unauthenticated($request, $exception);
            }
            if ($exception instanceof NotFoundHttpException) {
                return ReturnJson::fail("not found.", 404);
            }
            if ($exception instanceof Exception) {
                return ReturnJson::fail($exception->getMessage(), 500);
            }
        }
        return parent::render($request, $exception);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Illuminate\Auth\AuthenticationException $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return $request->expectsJson()
            ? ReturnJson::fail($exception->getMessage(), 401)
            : redirect()->guest(route('login'));
    }

    protected function validationHandle(ValidationException $exception)
    {
        $errors = $exception->errors();
        $errors = array_collapse($errors);
        $error = implode('|', $errors);
        return ReturnJson::fail($exception->getMessage() . ' -> ' . $error, 422);
    }

}
