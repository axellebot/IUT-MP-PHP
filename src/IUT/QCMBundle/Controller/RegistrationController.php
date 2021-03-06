<?php
/**
 * Created by PhpStorm.
 * User: Ilias
 * Date: 14/01/2016
 * Time: 18:12
 */

namespace IUT\QCMBundle\Controller;

use IUT\QCMBundle\Form\UserType;
use IUT\QCMBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class RegistrationController extends Controller
{
    /**
     * @Route("/register", name="user_registration")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function registerAction(Request $request)
    {
        // 1) build the form
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // 3) Encode the password (you could also do this via Doctrine listener)
            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $user->setRole("E");


            try {
                // 4) save the User!
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
            } catch (\Exception  $e) {
                $msg = '### Message ### \n' . $e->getMessage() . '\n### Trace ### \n' . $e->getTraceAsString();
                $this->container->get('logger')->critical($msg);
                // Here put you logic now you now that the flush has failed and all subsequent flush will fail as well

                return $this->render(
                    '@IUTQCM/security/register.html.twig',
                    array('form' => $form->createView(),
                        'globalMessage' => "E-mail ou Pseudo déjà utilisé"
                    )
                );
            }

            // ... do any other work - like send them an email, etc
            // maybe set a "flash" success message for the user

            return $this->redirect('/login');
        }

        return $this->render(
            '@IUTQCM/security/register.html.twig',
            array('form' => $form->createView(),
                'globalMessage'=>null)
        );
    }
}