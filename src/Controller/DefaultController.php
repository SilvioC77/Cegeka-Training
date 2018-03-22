<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="default")
     */
    public function index()
    {
        $entityManager = $this->getDoctrine();

        $repository = $entityManager->getRepository("App:Flight");
        $flights = $repository->findAll();

        $repository = $entityManager->getRepository("App:Hotel");
        $hotels = $repository->findAll();

        return $this->render('default/index.html.twig', array('flights'=>$flights,'hotels'=>$hotels));
    }
}
