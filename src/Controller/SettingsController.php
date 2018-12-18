<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Form\SettingsFormType;
use App\Form\MenuFormType;
use App\Form\CategoryForm;
use App\Entity\Settings;

use App\Service\SettingService;

class SettingsController extends AbstractController
{
    /**
     * @Route("/admin/settings", name="settings")
     */
    public function index(Request $request, SettingService $s)
    {

        $em = $this->getDoctrine()->getManager();

        // Get Settings
        $setting = $em->getRepository(Settings::class)->find(1);

        $form = $this->createForm(SettingsFormType::class, $setting);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $this->getDoctrine()->getManager()->flush();
        }


        $category = new \App\Entity\Menu;
        $menu = $this->createForm(MenuFormType::class, $category, ['em' => $em]);
        $menu->handleRequest($request);

        if ($menu->isSubmitted() && $menu->isValid()) {
            $data = $menu->getData();
            $em->persist($data);
            $em->flush();
        }

        $active_menu = $em->getRepository(\App\Entity\Menu::class)->findAll();

        return $this->render('settings/index.html.twig', [
            'current' => 'settings',
            'headline' => 'Settings',
            'base' => $s->get(),
            'active_menu' => $active_menu,
            'form' => $form->createView(),
            'menu' => $menu->createView()
        ]);
    }
}
