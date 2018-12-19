<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Form\MenuFormType;
use App\Entity\Menu;

use App\Service\SettingService;

class MenuController extends AbstractController
{
    /**
     * @Route("/admin/menu", name="menu")
     */
    public function index(Request $request, SettingService $s)
    {

        $em = $this->getDoctrine()->getManager();

        $category = new Menu();
        $menu = $this->createForm(MenuFormType::class, $category, ['em' => $em]);
        $menu->handleRequest($request);

        if ($menu->isSubmitted() && $menu->isValid()) {
            $data = $menu->getData();
            $em->persist($data);
            $em->flush();
        }

        $active_menu = $em->getRepository(\App\Entity\Menu::class)->findAll();

        return $this->render('menu/index.html.twig', [
            'menu' => $menu->createView(),
            'current' => 'menus',
            'active_menu' => $active_menu,
            'base' => $s->get()
        ]);
    }

    /**
     * @Route("/admin/menu/delete/{id}", name="menu_delete")
     */
    public function delete($id) {
        $em = $this->getDoctrine()->getManager();
        $menu = $em->getRepository(Menu::class)->find($id);

        $menu->setCategory(null);
        $em->remove($menu);
        $em->flush();

        $this->addFlash(
            'success',
            "Menu item has been unlinked successfully."
        );

        return $this->redirectToRoute('menu');
    }

}
