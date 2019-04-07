<?php

namespace App\Controller;

use App\Entity\Token;
use App\Entity\User;
use App\Form\RegistrationType;
use App\Repository\TokenRepository;
use App\Security\LoginFormAuthenticator;
use App\Services\TokenSendler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/registration", name="registration")
     */
    public function registration(Request $request, EntityManagerInterface $em, GuardAuthenticatorHandler $guardAuthenticatorHandler, LoginFormAuthenticator $loginFormAuthenticator, UserPasswordEncoderInterface $userPasswordEncoder, TokenSendler $sendler) {
        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $passwordEncoded = $userPasswordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($passwordEncoded);
            $user->setRoles(['ROLE_ADMIN']);

            $token = new Token($user);

            $em->persist($token);
            $em->flush();

            $sendler->sendToken($user, $token);

            $this->addFlash('notice', 'Un email de confirmation vous a été envoyé afin de terminer votre inscription');

            return $this->redirectToRoute('home');

//            return $guardAuthenticatorHandler->authenticateUserAndHandleSuccess(
//                $user,
//                $request,
//                $loginFormAuthenticator,
//                'main'
//            );
        }

        return $this->render('security/registration.html.twig', [
            'form'=> $form->createView(),
        ]);
    }

    /**
     * @Route("/confirmation/{value}", name="token_validation")
     */
    public function validateToken(Token $token, EntityManagerInterface $em, Request $request, GuardAuthenticatorHandler $guardAuthenticatorHandler, LoginFormAuthenticator $loginFormAuthenticator) {

        $user = $token->getUser();

        if($user->getEnable()) {
            $this->addFlash('notice', 'Ce token a déjà été validé');

            return $this->redirectToRoute('home');
        }

        if($token->isValid()) {

            $user->setEnable(true);
            $em->flush();

            return $guardAuthenticatorHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $loginFormAuthenticator,
                'main'
            );
        }

        $em->remove($token);

        $this->addFlash('notice', 'Le token est expiré');

        return $this->redirectToRoute('registration');

    }
}
