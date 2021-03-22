<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HelloWorldController
{
    
    public function sayHello()
    {
     /**
     * @Route("/hello-World");
     */
    
        return new Response(
            'Hello, World!'
        );
    }
}