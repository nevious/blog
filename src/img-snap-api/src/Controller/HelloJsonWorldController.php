<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Psr\Log\LoggerInterface;

use App\Service\GreetingProvider;


final class HelloJsonWorldController extends AbstractController {
    private GreetingProvider $greetingProvider;

    public function __construct(GreetingProvider $provider){
        $this->greetingProvider = $provider;
    }

    public function index(LoggerInterface $logger, ?string $name): JsonResponse {
        $logger->debug("Logging param $name from the controller!");

        $message = $name ? "Hello $name" : "Hello!";

        return $this->json([
            'message' => $message
        ]);
    }

    public function greet(LoggerInterface $logger, string $name): JsonResponse {
        $phrase = $this->greetingProvider->greet($name);
        $logger->debug("Greeting '$name' with phrase '$phrase'");

        return $this->json([
            'message' => $phrase
        ]);
    }

    public function post(Request $request): JsonResponse {
        return $this->json([
            'data' => $request->getPayload()->all()
        ], 201);
    }
}
