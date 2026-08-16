<?php

namespace App\Service;

class GreetingProvider {
    /* The following syntax basically sets $phrases as a class
     * property. I'm not entirely sure how this is done
     */
    public function __construct(private array $phrases) {}

    /**
     * Select a phrase from the Service config and return a formatted greeting phrase
     */
    public function greet(string $name) : string {
        $k = array_rand($this->phrases, 1);
        return sprintf($this->phrases[$k], $name);
    }
}
