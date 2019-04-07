<?php


namespace App\Controller;


use App\Entity\Car;
use App\Entity\Image;
use App\Entity\Keyword;
use App\Form\CarType;
use App\Repository\CarRepository;
use App\Services\ImageHandler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class homeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function homepage(CarRepository $carRepository) {

        $cars = $carRepository->findAll();

        return $this->render('app/index.html.twig', [
            'cars' => $cars
        ]);
        
    }


    /**
     * @Route("/car/add", name="add_page")
     */
    public function addCar(EntityManagerInterface $manager, Request $request, ImageHandler $handler) {

        $path = $this->getParameter('kernel.project_dir').'/public/img/';

        $form = $this->createForm(CarType::class, null, ['path' => $path]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $car = $form->getData();
            $user = $this->getUser();
            $car->setUser($user);

            $manager->persist($car);
            $manager->flush();

            $this->addFlash("notice", "Voiture ajoutée!");

            return $this->redirectToRoute("home");
        }

        return $this->render('app/add.html.twig', [
            'form' => $form->createView(),
        ]);

    }

    /**
     * @Route("/car/edit/{id}", name="edit_page")
     */
    public function editCar(EntityManagerInterface $manager, Car $car, Request $request) {

        $path = $this->getParameter('kernel.project_dir').'/public/img';
        $form = $this->createForm(CarType::class, $car, ['path' => $path]);
        $this->denyAccessUnlessGranted('EDIT', $car);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $path = $this->getParameter('kernel.project_dir').'/public/img';

            $manager->flush();
            $this->addFlash("notice", "La voiture a bien été modifiée");

            return $this->redirectToRoute("home", [
                'id' => $car->getId(),
            ]);
        }


        return $this->render('app/edit.html.twig', [
            'car' => $car,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/car/delete/{id}", name="delete_page")
     */
    public function deleteCar(EntityManagerInterface $manager, Car $car) {

        $this->denyAccessUnlessGranted('DELETE', $car);

        $manager->remove($car);

        $manager->flush();

        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/show/{id}", name="car_page")
     */
    public function getCar(Car $car) {

        return $this->render('app/car.html.twig', [
            'car' => $car
        ]);
    }

    /**
     * @Route("/symfony/contact", name="contact_page")
     */
    public function contact() {
        return $this->render('app/contact.html.twig');
    }

    /**
     * @Route("delete/keyword/{id}",
     *     name="delete_keyword",
     *     methods={"POST"},
     *     condition="request.headers.get('X-Requested-With') matches '/XMLHttpRequest/i'")
     */
    public function deleteKeyword(Keyword $keyword, EntityManagerInterface $manager) {
        $manager->remove($keyword);
        $manager->flush();

        return new JsonResponse();
    }

}