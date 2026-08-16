<?php

namespace App\DataFixtures;

/**
 * Not a framework fixture
 */
class ImageFixtures {
    public static function loadStringImage(): string {
        $s = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8BQDwAEhQGAhKmMIQAAAABJRU5ErkJggg==';
        return base64_decode($s);
    }

    public static function placeholdCoImageUrl600x400(): string {
        return "https://placehold.co/1000x400/222327/72ccEB.webp?font=noto-sans&text=SomeText";
    }
}
