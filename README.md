# Bag2\OAuth\PKCE

PHP [RFC 7636 - Proof Key for Code Exchange by OAuth Public Clients][rfc7636] (PKCE) implementaion independent of OAuth servers.

[rfc7636]: https://tools.ietf.org/html/rfc7636

## Why this package?

Already known OAuth2 server implementations (eg [league/oauth2-server]) implement PKCE, but not servers based on the latest implementation. This library provides functionality for adding PKCE verification to an independent OAuth server.

[league/oauth2-server]: https://oauth2.thephpleague.com/

## Usage

### 1. Get `code_verifier` and `code_method` in **step (A)**

See **Figure 3: Authorization Code Flow** in [OAuth 2.0: 4.1.  Authorization Code Grant](https://tools.ietf.org/html/rfc6749#section-4.1).

In this flow, write as follows:

```php
// This (pseudo) code is written in vanilla PHP.
// Actually follow your framework / project conventions.

use Bag2\OAuth\PKCE\Verifier as PKCEVerifier;

// Request by Web Browser
$code_verifier = \filter_input(INPUT_GET, 'code_verifier');
$code_challenge_method = \filter_input(INPUT_GET, 'code_challenge_method') ?: 'plain';

if ($code_verifier !== null) {
    if (!Verifier::isValidCodeVerifier($code_verifier)) {
        throw new Exception('invalid code_verifier');
    }
    if (!Verifier::isValidCodeChallengeMethod($code_challenge_method)) {
        throw new Exception('invalid code_challenge_method');
    }
}

store_value([
    'code' => getnerate_oauth_code(),
    'code_verifier' => $code_verifier,
    'code_challenge_method' => $code_challenge_method,
]);

// Redirect
```

### 2. Verify `code_challenge` in **step (D)**

```php
// This (pseudo) code is written in vanilla PHP.
// Actually follow your framework / project conventions.

use Bag2\OAuth\PKCE\Verifier as PKCEVerifier;

// Request by Client
$code = \filter_input(INPUT_POST, 'code');
$saved = get_stored_value($code);

$code_challenge = \filter_input(INPUT_POST, 'code_challenge');

if (isset($saved['code_verifier'])) {
    if ($code_challenge === null) {
        throw new Exception('code_challenge required');
    }

    $verifier = PKCEVerifier::fromArray($saved);
    if (!$verifier->verify($code_challenge)) {
        throw new Exception('code_challenge required');
    }
}

// Return generated Access Token
```

## Copyright

This package is licenced under [Apache License 2.0][Apache-2.0].

> Copyright 2019 Baguette HQ
>
> Licensed under the Apache License, Version 2.0 (the "License");
> you may not use this file except in compliance with the License.
> You may obtain a copy of the License at
>
>     http://www.apache.org/licenses/LICENSE-2.0
>
> Unless required by applicable law or agreed to in writing, software
> distributed under the License is distributed on an "AS IS" BASIS,
> WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
> See the License for the specific language governing permissions and
> limitations under the License.

[Apache-2.0]: https://www.apache.org/licenses/LICENSE-2.0
