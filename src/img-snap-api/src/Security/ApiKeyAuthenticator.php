<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\InMemoryUser;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class ApiKeyAuthenticator extends AbstractAuthenticator {
    // Inject the env-based key
    public function __construct(
        private string $apiKey
    ) {}

    // What URLS do we support?
    public function supports(Request $request): ?bool {
        return str_starts_with($request->getPathInfo(), '/image');
    }

    // Do the authenticating if the Api key matches
    public function authenticate(Request $request): Passport {
        $apiToken = $request->headers->get("X-AUTH-TOKEN");

        if ($apiToken === null || $apiToken !== $this->apiKey) {
            throw new CustomUserMessageAuthenticationException("Invalid or missing API Key.");
        }

        return new SelfValidatingPassport(
            new UserBadge('api_user', function() {
                    return new InMemoryUser('api_user', null, ['ROLE_API_USER']);
            })
        );
    }

    // If the authentication succeeeds
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response {
        // Returning null allows the request to proceed to your Controller
        return null;
    }

    // If the authentication fails
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response {
        return new JsonResponse([
            'message' => $exception->getMessageKey()
        ], Response::HTTP_UNAUTHORIZED);
    }

}
