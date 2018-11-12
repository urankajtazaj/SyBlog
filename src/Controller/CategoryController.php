<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\CategoryForm;
use App\Entity\Category;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category/add", name="category_add")
     */
    public function index(Request $request)
    {
        $category = new Category();
        $form = $this->createForm(CategoryForm::class, $category);

        $responseType = null;
        $responseMessage = null;

        $form->handleRequest($request);

        if ($request->isMethod('POST')) {
            if ($form->isSubmitted() && $form->isValid()) {
                $data = $form->getData();

                $em = $this->getDoctrine()->getManager();
                $em->persist($data);
                $em->flush();

                return $this->redirectToRoute('post_list');
            }
        }

        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
            'form' => $form->createView(),
            'title' => 'New Category'
        ]);
    }

        /**
     * @Route("/category/edit/{id}", name="category_edit")
     */
    public function edit(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository(Category::class)->find($id);

        $form = $this->createForm(CategoryForm::class, $category);

        $form->handleRequest($request);

        if ($request->isMethod('POST')) {
            if ($form->isSubmitted() && $form->isValid()) {
                $data = $form->getData();

                $em = $this->getDoctrine()->getManager();
                $em->flush();

                return $this->redirectToRoute("category_add");
            }
        }

        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
            'form' => $form->createView(),
            'title' => 'New Category'
        ]);
    }

}
