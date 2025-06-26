<?php

namespace Cherryred5959\ChzzkApi\Auth;

enum TokenTypeHint: string
{
    case Access = "access_token";

    case Refresh = "refresh_token";

    public function getToken(AccessToken $accessToken): string
    {
        return match ($this) {
            self::Access => $accessToken->getAccessToken(),
            self::Refresh => $accessToken->getRefreshToken(),
        };
    }
}
