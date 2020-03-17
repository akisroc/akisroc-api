<?php declare(strict_types=1);

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Class SecurityController
 * @package App\Controller
 */
final class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="security.login")
     *
     * @param Request $request
     * @param AuthenticationUtils $authUtils
     *
     * @return Response
     */
    public function login(Request $request, AuthenticationUtils $authUtils): Response
    {
        if ($this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('homepage');
        }

        return $this->render('user/login.html.twig', [
            'last_username' => $authUtils->getLastUsername(),
            'error' => $authUtils->getLastAuthenticationError()
        ]);
    }

    /**
     * @Route("/logout", name="security.logout")
     *
     * @return void
     *
     * @throws \RuntimeException
     */
    public function logout(): void
    {
        throw new \RuntimeException('This route should not be reached.');
    }
}
