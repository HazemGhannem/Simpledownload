<?php
/**
 * This file is part of the ADWYA Solutions project.
 * Portail Validation de PV.
 *
 * (c) ECHO, Software development 2021
 * @author Skander SMAOUI <ssmaoui@echo.tn>
 *
 */

declare(strict_types=1);

namespace App\Application\Twig;


use App\Application\Menu\BackendSidebarMenu;
use App\Application\Menu\Model\Menu;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class GlobalApplication
{

    /**
     * @var UrlGeneratorInterface
     */
    protected $urlGenerator;
    /**
     * @var $siteUrl
     */
    protected $siteUrl;

    private $params;
    private $user;
    /**
     * DiagnosticPresentation constructor.
     *
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(
        UrlGeneratorInterface $urlGenerator,
        ParameterBagInterface $params,
        TokenStorageInterface $tokenStorage
    )
    {
        $this->urlGenerator         = $urlGenerator;
        $this->params               = $params;
        $this->user = $tokenStorage->getToken() ? $tokenStorage->getToken()->getUser() : null;
    }


    /**
     * @param string $currentRouteName
     *
     * @return Menu
     */
    public function getBackendSidebarMenu(string $currentRouteName): Menu
    {
        $menu = new BackendSidebarMenu($this->user);

        return $menu->build($currentRouteName);
    }

    /**
     *
     * @return string
     */
    public function getSiteUrl(): string {
        $dotenv = new Dotenv();
        $dotenv->load($this->params->get('kernel.project_dir').'/.env', $this->params->get('kernel.project_dir').'/.env.local');
        $this->siteUrl = $_ENV['SITE_URL'];
        return $this->siteUrl;

    }
}
