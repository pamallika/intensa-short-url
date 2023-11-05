<?php

namespace Projects\Intensa\Controllers;

use Laminas\Diactoros\ServerRequest;
use Projects\Intensa\Models\Links;

class LinkController
{
    public function index(): void
    {
        include 'index.html';
    }

    public function createShortUrl(ServerRequest $request): bool|string
    {
        $data = $request->getParsedBody();
        $uri = $data['uri'] ?? null;
        if (!$uri) {
            return response([], 'key "uri" not found', UNPROCESSABLE_CONTENT);
        }
        if (!filter_var($uri, FILTER_VALIDATE_URL)) {
            return response([], 'error validation uri', UNPROCESSABLE_CONTENT);
        }
        $urlGeneratedData = $this->generateShortUrlData($uri);
        $urls = new Links();
        $urlData = $urls->findBy(['short_url_hash' => $urlGeneratedData['short_url_hash']])->exec();
        if (empty($urlData)) {
            $urlData = $urls->insert($urlGeneratedData)->exec();
        }

        return response($urlData);
    }

    protected function generateShortUrlData(string $uri): array
    {
        /** @var string $md5 short_url_hash */
        $md5 = hash('md5', $uri);
        /** Добавляем в конец crc хэша первые два символа md5 хэша чтобы минимизировать риск коллизий */
        $crc = hash('crc32', $uri);
        $shorUrl = $crc . mb_substr($md5, 0, 2);

        return ['origin_uri' => $uri, 'short_url' => $shorUrl, 'short_url_hash' => $md5];
    }

    public function checkUrl(string $shortLink): bool|string
    {
        $urls = new Links();
        $data = $urls->findBy(['short_url' => $shortLink])->exec();

        if (empty($data)) {
            return response($data, 'Link Not Found', NOT_FOUND);
        }
        header('Location: ' . $data[0]['origin_uri']);
        return response([]);
    }
}