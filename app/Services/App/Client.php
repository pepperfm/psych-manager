<?php

namespace App\Services\App;

use Psr\Http\Message\ServerRequestInterface;

final class Client
{
    public function __construct(private string $clientId, private string $clientSecret) {}

    private function client(ServerRequestInterface $request): ?ServerRequestInterface
    {
        return $request->withParsedBody([
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'grant_type' => $request->getParsedBody()['grant_type'],
            'username' => $request->getParsedBody()['username'],
            'password' => $request->getParsedBody()['password']
        ]);
    }

    public function init(ServerRequestInterface $request): ?ServerRequestInterface
    {
        return $this->client($request);
    }
}
