<?php
/**
 * This file is part of the DAMJ Documents Projects
 *
 * (c) ECHO, Software development 2021
 * @author Skander SMAOUI <ssmaoui@echo.tn>
 *
 */


namespace App\Application\Menu\Model;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Item
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $icon;

    /**
     * @var string[]
     */
    protected $routes;

    /**
     * @var Collection|Item[]
     */
    protected $children;

    /**
     * @var bool
     */
    protected $divider;

    /**
     * @var Menu
     */
    protected $menu;

    public function __construct(string $name, ?string $icon = null, array $routes = [], array $children = [], bool $divider = false)
    {
        $this->name     = $name;
        $this->icon     = $icon;
        $this->routes   = $routes;
        $this->children = new ArrayCollection($children);
        $this->divider  = $divider;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getIcon(): ?string
    {
        return $this->icon;
    }

    /**
     * @return string[]
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        $routes = $this->routes;

        foreach ($this->children as $child) {
            foreach ($child->getRoutes() as $route) {
                $routes[] = $route;
            }
        }

        return in_array($this->menu->getCurrentRouteName(), $routes, true);
    }

    /**
     * @return Item[]|Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @return bool
     */
    public function hasChildren(): bool
    {
        return !$this->children->isEmpty();
    }

    /**
     * @param Menu $menu
     */
    public function setMenu(Menu $menu): void
    {
        $this->menu = $menu;
    }

    /**
     * @return Menu
     */
    public function getMenu(): Menu
    {
        return $this->menu;
    }

    /**
     * @return string|null
     */
    public function getFirstRoute(): ?string
    {
        if (array_key_exists(0, $this->routes)) {
            return $this->routes[0];
        }

        return null;
    }

    /**
     * @return bool
     */
    public function hasDivider(): bool
    {
        return $this->divider;
    }
}
