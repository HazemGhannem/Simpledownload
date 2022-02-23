<?php
/**
 * This file is part of the DAMJ Documents Projects
 *
 * (c) ECHO, Software development 2021
 * @author Skander SMAOUI <ssmaoui@echo.tn>
 *
 */


namespace App\Application\Menu;


use App\Application\Menu\Model\Menu;

interface MenuBuilderInterface
{
    /**
     * @param string $currentRouteName
     *
     * @return Menu
     */
    public function build(string $currentRouteName): Menu;
}
