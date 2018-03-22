<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SilvioTestController extends Controller
{
    /**
     * @Route("/silvio/test", name="silvio_test")
     */
    public function index()
    {
        return $this->render('silvio_test/index.html.twig', [
            'controller_name' => 'SilvioTestController',
        ]);
    }
}
