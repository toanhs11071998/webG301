<?php

namespace App\Controller;

use App\Entity\Student;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StudentController extends AbstractController
{
	/**
	 *
	 * @Route ("/students", name="student_index")
	 */
    public function index(ManagerRegistry $doctrine): Response
		{
			$students = $doctrine->getRepository(Student::class)->findAll();
        return $this->render('student/index.html.twig', [
            'students' => $students
        ]);
    }
}
