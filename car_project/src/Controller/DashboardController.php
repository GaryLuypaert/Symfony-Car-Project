<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    /**
     * @Route("/dashboard", name="dashboard_user")
     */
    public function dashboard() {
        return $this->render('app/dashboard.html.twig', [
            'cars' => $this->getUser()->getCars(),
        ]);
    }
}