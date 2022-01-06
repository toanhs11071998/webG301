<?php

namespace App\Controller;

use App\Entity\Student;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
	/**
	 * @Route("/home", name="home")
	 */
    public function index(ManagerRegistry $doctrine): Response
    {
			$students = $doctrine->getRepository(Student::class)->findAll();
        return $this->render('home/index.html.twig', [
						'student' => count($students)
				]);
    }
}
