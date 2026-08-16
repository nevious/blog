<?php

namespace App\Tests\Service;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Service\GreetingProvider;

class GreetingServiceTest extends KernelTestCase
{
    private ?GreetingProvider $greeter;

    public function setUp(): void {
        self::bootKernel();
        $this->greeter = static::getContainer()->get(GreetingProvider::class);
    }

    public function testGreetingService() {
        $result = $this->greeter->greet("test name");
        $this->assertIsString($result);
        $this->assertNotNull($result);
        $this->assertStringContainsString('test name', $result);
    }
}
