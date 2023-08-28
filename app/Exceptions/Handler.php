<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Throwable;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    protected $exceptionStatusMap = [
        TokenMismatchException::class => Response::HTTP_UNAUTHORIZED, //401
        AuthenticationException::class => Response::HTTP_UNAUTHORIZED, //401
        AuthorizationException::class => Response::HTTP_FORBIDDEN, //403
        AccessDeniedHttpException::class => Response::HTTP_FORBIDDEN, //403
        UnauthorizedHttpException::class => Response::HTTP_UNAUTHORIZED, //401
        NotFoundHttpException::class => Response::HTTP_NOT_FOUND, //404
        ModelNotFoundException::class => Response::HTTP_NOT_FOUND, //404
        MethodNotAllowedHttpException::class => Response::HTTP_METHOD_NOT_ALLOWED, //405
        ValidationException::class => Response::HTTP_UNPROCESSABLE_ENTITY, //422
        HttpException::class => Response::HTTP_INTERNAL_SERVER_ERROR, //500
        HttpResponseException::class => Response::HTTP_INTERNAL_SERVER_ERROR, //500
        QueryException::class => Response::HTTP_INTERNAL_SERVER_ERROR //500
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        $statusCode = $this->getStatusCode($exception);
        $responseData = [
            'errors' => $statusCode === 422 ? $exception->errors() : [],
            'exception' => get_class($exception),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'message' => $exception->getMessage(),
            'statusCode' => $statusCode,
        ];
        return response()->json($responseData, $statusCode);
    }

    protected function getStatusCode(Throwable $exception): int
    {
        foreach ($this->exceptionStatusMap as $exceptionClass => $statusCode) {
            if ($exception instanceof $exceptionClass) {
                return $statusCode;
            }
        }
        return 501;
    }
}
