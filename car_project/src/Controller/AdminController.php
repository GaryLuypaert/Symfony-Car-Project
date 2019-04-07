<?php


namespace App\Controller;


use App\Entity\Token;
use App\Entity\User;
use App\Form\RegistrationType;
use App\Repository\UserRepository;
use App\Security\LoginFormAuthenticator;
use App\Services\TokenSendler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function admin(UserRepository $userRepository) {

        $userRepository = $userRepository->findAll();

        return $this->render('admin/admin.html.twig', [
            'users' => $userRepository,
        ]);
    }

    /**
     * @Route("/admin/delete/{id}", name="delete_user")
     */
    public function deleteUser(User $user, EntityManagerInterface $manager, UserRepository $userRepository) {

        $userRepository = $userRepository->findAll();

        $manager->remove($user);
        $manager->flush();

        $this->addFlash("notice", "L'utilisateur a bien été supprimé");

        return $this->render('admin/admin.html.twig', [
            'users' => $userRepository,
        ]);
    }

}