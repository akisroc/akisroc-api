<?php

declare(strict_types=1);

namespace App\Utils\Menu;

use App\Entity\User;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class MenuHelper
 * @package App\Utils
 */
final class MenuHelper
{
    /** @var TranslatorInterface $translator */
    private TranslatorInterface $translator;

    /** @var UrlGeneratorInterface $urlGenerator */
    private UrlGeneratorInterface $urlGenerator;

    /** @var TokenStorageInterface $tokenStorage */
    private TokenStorageInterface $tokenStorage;

    /**
     * MenuHelper constructor.
     * @param TranslatorInterface $translator
     * @param UrlGeneratorInterface $urlGenerator
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(
        TranslatorInterface $translator,
        UrlGeneratorInterface $urlGenerator,
        TokenStorageInterface $tokenStorage
    ) {
        $this->translator = $translator;
        $this->urlGenerator = $urlGenerator;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @return Menu
     */
    public function getMainMenu(): Menu
    {
        $menu = new Menu();

        $menu
            ->addNode((new Node())
                ->setHref($this->urlGenerator->generate('homepage'))
                ->setLabel($this->translator->trans('navigation.homepage'))
            )
            ->addNode((new Node())
                ->setHref('#')
                ->setLabel($this->translator->trans('navigation.private_messages'))
            )
        ;

        if ($this->tokenStorage->getToken()->getUser() instanceof User) {
            $menu
                ->addNode((new Node())
                    ->setHref($this->urlGenerator->generate('security.logout'))
                    ->setLabel($this->translator->trans('navigation.logout'))
                )
            ;
        } else {
            $menu
                ->addNode((new Node())
                    ->setHref($this->urlGenerator->generate('security.login'))
                    ->setLabel($this->translator->trans('navigation.login'))
                )
                ->addNode((new Node())
                    ->setHref($this->urlGenerator->generate('user.register'))
                    ->setLabel($this->translator->trans('navigation.register'))
                )
            ;
        }

        return $menu;
    }
}
