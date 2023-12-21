<?php

namespace App\Tests\Repository;

use App\Entity\DateV;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class DateVRepositoryTest extends KernelTestCase
{
    public function testFindByDateRange(): void
    {
        $kernel = self::bootKernel();

        $this->assertSame('test', $kernel->getEnvironment());
        // $routerService = self::$container->get('router');
        // $myCustomService = self::$container->get(CustomService::class);
    }
}
