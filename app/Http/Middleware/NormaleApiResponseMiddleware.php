<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class NormaleApiResponseMiddleware
{
    /**
     * @var bool
     */
    protected bool $success = true;

    /**
     * @var null|object
     */
    protected mixed $data = null;

    /**
     * @var string
     */
    protected string $message = '';

    /**
     * @var int
     */
    protected mixed $code = 0;

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $response = $next($request);

        if ($response instanceof \Symfony\Component\HttpFoundation\BinaryFileResponse) {
            return $response;
        }

        $this->normalizeResponse($response);
        $status = $response->status();
        if ($status >= 400) {
            $this->success = false;
        }
        return response()->json([
            'success' => $this->success,
            'message' => $this->message,
            'code'    => $this->code,
            'data'    => $this->data,
        ], $response->status());
    }

    /**
     * @param mixed $response
     * @return void
     */
    private function normalizeResponse(mixed $response): void
    {
        $response instanceof JsonResponse ? $this->normalizeJsonResponse($response) : $this->normaliseHttpResponse($response);
    }

    /**
     * @param Response $response
     * @return void
     */
    private function normaliseHttpResponse($response): void
    {
        $exception = $response->exception;
        $this->success = is_null($exception);
        $this->message = $this->success ? 'Success!' : $exception->getMessage();
        $this->code = $this->success ? 0 : $exception->getCode();
        $this->data = $this->success ? $this->normalieResponseData($response->content()) : null;
    }

    /**
     * @param mixed $data
     * @return mixed
     */
    private function normalieResponseData(mixed $data)
    {
        if (is_object($data) || is_array($data)) {
            return $data;
        }
        if (is_null($data)) {
            return null;
        }

        return ['data' => $data];
    }

    /**
     * @param JsonResponse $response
     * @return void
     */
    private function normalizeJsonResponse(JsonResponse $response): void
    {
        $data = $response->getData();
        $this->success = $data->success ?? true;
        $this->message = $this->message($data);
        $this->code = $data->code ?? 0;
        $responseData = $this->getDataFromJsonResponse($data);
        $this->data = $this->normalieResponseData($responseData);
    }

    /**
     * @param mixed $data
     * @return mixed|null
     */
    private function getDataFromJsonResponse(mixed $data)
    {
        if (isset($data->success)) {
            return data_get($data, 'data');
        }
        return $data;
    }

    /**
     * @param mixed $data
     * @return string
     */
    private function message(mixed $data): string
    {
        if (isset($data->message)) {
            return $data->message;
        }

        return $this->success ? 'Success!' : 'Error!';
    }
}
