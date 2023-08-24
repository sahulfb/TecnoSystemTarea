<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Throwable;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;

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
        NotFoundHttpException::class => Response::HTTP_NOT_FOUND, //404
        ModelNotFoundException::class => Response::HTTP_NOT_FOUND, //404
        MethodNotAllowedHttpException::class => Response::HTTP_METHOD_NOT_ALLOWED, //405
        UnauthorizedHttpException::class => Response::HTTP_UNAUTHORIZED, //401
        AuthenticationException::class => Response::HTTP_UNAUTHORIZED, //401
        ValidationException::class => Response::HTTP_UNPROCESSABLE_ENTITY //422
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
            'errors' => $exception->getMessage(),
            'exception' => get_class($exception),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'statusCode' => $statusCode,
        ];
        return new JsonResponse($responseData, $statusCode);
    }

    protected function getStatusCode(Throwable $exception): int
    {
        foreach ($this->exceptionStatusMap as $exceptionClass => $statusCode) {
            if ($exception instanceof $exceptionClass) {
                return $statusCode;
            }
        }
        return Response::HTTP_INTERNAL_SERVER_ERROR;
    }
}
