<?php
/**
 * This file is part of the DAMJ Documents Projects
 *
 * (c) ECHO, Software development 2021
 * @author Skander SMAOUI <ssmaoui@echo.tn>
 *
 */


namespace App\Application\Menu;


use App\Application\Menu\Model\Item;
use App\Application\Menu\Model\Menu;
use App\Entity\User;

class BackendSidebarMenu implements MenuBuilderInterface
{

/*
        private $user;

        public function __construct(User $user)
        {
            $this->user = $user;
        }

*/
    /**
     * @param string $currentRouteName
     *
     * @return Menu
     */
    public function build(string $currentRouteName): Menu
    {
        $menu = new Menu($currentRouteName);
        

        $menu->addItem(
            new Item('Offre emplois', 'icon-user-lock', ['list_demande_formateur'], [
                new Item('Offres Emplois', null, ['list_demande_formateur']),
                new Item('Nouveau', null, ['Adddemandeformateur']),
            ]
        ));

       


        return $menu;
    }
}
