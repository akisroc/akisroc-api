<?php

namespace App\Controller;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;

/**
 * Class SecurityController
 * @package App\Controller
 */
class SecurityController extends AbstractFOSRestController
{
    /**
     * @Rest\Route("/login", name="login")
     *
     * @return void
     */
    public function login(): void
    {
        throw new \RuntimeException('This code should not be reached.');
    }
}
