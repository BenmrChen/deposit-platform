<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Passport\Exceptions\OAuthServerException;
use League\OAuth2\Server\Exception\OAuthServerException as LeagueOAuthServerException;
use Symfony\Component\Console\Exception\CommandNotFoundException;
use Symfony\Component\Console\Exception\InvalidArgumentException as ConsoleInvalidArgumentException;
use Symfony\Component\Console\Exception\InvalidOptionException as ConsoleInvalidOptionException;
use Symfony\Component\Console\Exception\RuntimeException as ConsoleRuntimeException;
use Symfony\Component\HttpKernel\Exception\HttpException as SymfonyHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $dontReport = [
        PointException::class,
        AppException::class,
        NotFoundHttpException::class,
        AuthenticationException::class,
        MethodNotAllowedHttpException::class,
        ValidationException::class,
        LeagueOAuthServerException::class,
        OAuthServerException::class,
        CommandNotFoundException::class,
        ConsoleInvalidArgumentException::class,
        ConsoleInvalidOptionException::class,
        ConsoleRuntimeException::class,
    ];

    protected $dontFlash = [];

    public function register()
    {
        $this->reportable(function (Throwable $exception) {
            return rescue(
                function () use ($exception) {
                    if (!empty(config('sentry.dsn')) && app()->bound('sentry')) {
                        app('sentry')->captureException($exception);

                        return false;
                    }

                    return true;
                },
                true,
                false
            );
        });

        $this->renderable(function (Throwable $exception) {
            if ($exception instanceof AuthenticationException && !is_null($exception->redirectTo())) {
                return null;
            }

            $statusCode = 500;
            $message    = null;
            $errors     = [];

            $status = config('status_code');

            /**
             * @var \Illuminate\Routing\Route
             */
            $route = Request::route();

            switch (get_class($exception)) {
                case AuthenticationException::class:
                    // @phpstan-ignore-next-line
                    if (isset($route) && Str::contains($route->getAction()['namespace'], 'PointExchange')) {
                        $code    = '000-01';
                        $message = $status[$code]['message'];
                    } else {
                        $statusCode = 401;
                        $message    = 'unauthenticated';
                        $errors[]   = compact('message');
                    }

                    break;
                case NotFoundHttpException::class:
                    // @phpstan-ignore-next-line
                    if (isset($route) && Str::contains($route->getAction()['namespace'], 'PointExchange')) {
                        $code    = '000-02';
                        $message = $status[$code]['message'];
                    } else {
                        $statusCode = 404;
                        $message    = 'notFound';
                        $errors[]   = compact('message');
                    }

                    break;
                case MethodNotAllowedHttpException::class:
                    // @phpstan-ignore-next-line
                    if (isset($route) && Str::contains($route->getAction()['namespace'], 'PointExchange')) {
                        $code    = '000-03';
                        $message = $status[$code]['message'];
                    } else {
                        $statusCode = 405;
                        $message    = 'methodNotAllowed';
                        $errors[]   = compact('message');
                    }

                    break;
                case ValidationException::class:
                    /** @var ValidationException $exception */
                    // @phpstan-ignore-next-line
                    if (isset($route) && Str::contains($route->getAction()['namespace'], 'PointExchange')) {
                        $code    = '000-04';
                        $message = $status[$code]['message'];
                    } else {
                        $statusCode = 422;
                    }

                    foreach ($exception->errors() as $field => $messages) {
                        foreach ($messages as $message) {
                            $errors[] = compact('field', 'message');
                        }
                    }

                    break;
                case ThrottleRequestsException::class:
                    // @phpstan-ignore-next-line
                    if (isset($route) && Str::contains($route->getAction()['namespace'], 'PointExchange')) {
                        $code    = '000-05';
                        $message = $status[$code]['message'];
                    } else {
                        $statusCode = 429;
                        $message    = 'tooManyAttempts';
                        $errors[]   = compact('message');
                    }

                    break;
                default:
                    $statusCode = 500;
                    $message    = 'unknownError';
                    $errors[]   = compact('message');

                    break;
            }

            if ($exception instanceof SymfonyHttpException && App::isDownForMaintenance()) {
                // @phpstan-ignore-next-line
                if (isset($route) && Str::contains($route->getAction()['namespace'], 'PointExchange')) {
                    $code    = '005-01';
                    $message = $status[$code]['message'];
                } else {
                    $statusCode = 503;
                    $message    = 'IN MAINTENANCE';
                    $errors     = [compact('message')];
                }
            }

            $status = [
                'code'    => $code ?? $statusCode,
                'message' => $message,
            ];

            if (env('APP_DEBUG')) {
                $errors['moreInfo'] = [
                    'message' => $exception->getMessage(),
                    'file'    => $exception->getFile(),
                    'line'    => $exception->getLine(),
                ];
            }

            // @phpstan-ignore-next-line
            if (isset($route) && Str::contains($route->getAction()['namespace'], 'PointExchange')) {
                $statusCode = 200;
                $data       = ['detail' => $errors];
                $response   = compact('status', 'data');
            } else {
                $response = compact('errors');
            }

            return response()->json($response, $statusCode);
        });
    }
}
