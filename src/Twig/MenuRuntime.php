<?php

declare(strict_types=1);

namespace App\Twig;

use App\Utils\Menu\Menu;
use App\Utils\Menu\MenuHelper;
use Twig\Extension\RuntimeExtensionInterface;

/**
 * Class MenuRuntime
 * @package App\Twig
 */
final class MenuRuntime implements RuntimeExtensionInterface
{
    /** @var MenuHelper $menuHelper */
    private MenuHelper $menuHelper;

    /**
     * MenuRuntime constructor.
     * @param MenuHelper $menuHelper
     */
    public function __construct(MenuHelper $menuHelper)
    {
        $this->menuHelper = $menuHelper;
    }

    public function getMainMenu(): Menu
    {
        return $this->menuHelper->getMainMenu();
    }
}
