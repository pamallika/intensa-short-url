<?php
const UNPROCESSABLE_CONTENT = 422;
const NOT_FOUND = 422;
const OK = 200;
if (!function_exists('response')) {
    function response(array $data, string $error = '', int $status = 200): bool|string
    {
        http_response_code($status);
        return json_encode(['data' => $data, 'errors' => $error]);
    }
}