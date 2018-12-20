<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;
use App\Form\UserType;

use App\Service\SettingService;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="register")
     */
    public function index(Request $request, SettingService $setting, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $pw = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($pw);

            if ($form['admin']->getData() == 'a') {
                $user->setRoles(["ROLE_ADMIN"]);
            } else if ($form['admin']->getData() == 'w') {
                $user->setRoles(["ROLE_WRITER"]);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute("app_login");
        }

        return $this->render('registration/index.html.twig', [
            'current' => 'users',
            'headline' => 'Add User',
            'base' => $setting->get(),
            'menu' => $setting->getMenu(),
            'form' => $form->createView()
        ]);
    }
}
