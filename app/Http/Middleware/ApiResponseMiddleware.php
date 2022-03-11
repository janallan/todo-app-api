<?php

namespace App\Http\Middleware;

use Closure;
use stdClass;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;

class ApiResponseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        // Check if response is a redirect response
        if ($response instanceof RedirectResponse) {
            return $response;
        }

        // Check if response expects json response
        if (!($response instanceof JsonResponse)) {
            return $response;
        }

        $data = $response->getData();

        if (!is_object($data)) {
            $data = new stdClass();
        }

        if (!property_exists($data, 'http_status')) {
            $data->http_status = $response->status();
        }

        $success = $response->status() < 400;

        if (!property_exists($data, 'success')) {
            $data->success = $success;
        }

        // Add error code when the request has error
        if (!property_exists($data, 'error_code') && !$success) {
            $data->error_code = $this->getStatusKey($response->status());
        }

        // If request has validation error
        // Set the data message as the first validation error message
        if ($response->status() === 422) {
            if (property_exists($data, 'errors')) {
                $message = $this->getFirstErrorMessage($data->errors);
                if ($message) {
                    $data->message = $message;
                }
            }
        }

        $response->setData($data);

        return $response;
    }

    /**
     * Get key that represents an HTTP status code
     *
     * @param Int $code - http status code
     * @return String $key
     */
    private function getStatusKey($code): String
    {
        $reflectionClass = new \ReflectionClass(Response::class);
        return array_search($code, $reflectionClass->getConstants(), true);
    }

    /**
     * Get the first error message
     * @return String or null
     */
    private function getFirstErrorMessage($errors): ?String
    {
        $errorsArray = (array) $errors;
        if (count($errorsArray) > 0) {
            $firstError = current($errorsArray);
            if (count($firstError) > 0 && $firstError[0]) {
                return $firstError[0];
            }
        }
    }
}
