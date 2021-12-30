<?php

namespace App\Controller;

use App\Entity\Student;
use App\Form\StudentType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StudentController extends AbstractController
{

		public function __construct(ManagerRegistry $doctrine){
			$this->doctrine = $doctrine;
		}
		/**
		 * @Route ("/students", name="student_index")
		 */
    public function index(): Response
		{
			$students = $this->doctrine->getRepository(Student::class)->findAll();
        return $this->render('student/index.html.twig', [
            'students' => $students
        ]);
    }

		/**
		 * @Route ("/students/detail/{id}", name="student_detail")
		 */
		public function detail($id): Response
		{
			$student = $this->doctrine->getRepository(Student::class)->find($id);
			if (!$student){
				$this->addFlash("Error", "Student Not Found");
				return $this->redirectToRoute("student_index");
			}

			return $this->render('student/detail.html.twig', [
					"student" => $student
			]);
		}

		/**
		 * @Route ("/students/create", name="student_create")
		 */
		public function create(Request $request): Response
		{
			$student = new Student();
			$form = $this->createForm(StudentType::class, $student);
			$form->handleRequest($request);

			if ($form->isSubmitted() && $form->isValid()){
				$manager  = $this->doctrine->getManager();
				$manager->persist($student);
				$manager->flush();
				$this->addFlash('Success', "Add Student Succeeded");
				return $this->redirectToRoute('student_index');
			}
			return $this->renderForm('student/create.html.twig',
				[
						'form' => $form
				]);
		}

		/**
		 * @Route ("/students/edit/{id}", name="student_edit")
		 */
		public function edit(Request $request, $id): \Symfony\Component\HttpFoundation\RedirectResponse|Response
		{
			$student = $this->doctrine->getRepository(Student::class)->find($id);
			if (!$student){
				$this->addFlash("Error", "Student Not Found");
				return $this->redirectToRoute("student_index");
			}

			$form = $this->createForm(StudentType::class, $student);
			$form->handleRequest($request);

			if ($form->isSubmitted() && $form->isValid()){
				$manager  = $this->doctrine->getManager();
				$manager->persist($student);
				$manager->flush();
				$this->addFlash('Success', "Edit Student Succeeded");
				return $this->redirectToRoute('student_index');
			}
			return $this->renderForm('student/edit.html.twig',
					[
							'form' => $form
					]);
		}

		/**
		 * @Route ("/students/delete/{id}", name="student_delete")
		 */
		public function delete($id)
		{
			$student = $this->doctrine->getRepository(Student::class)->find($id);
			if (!$student){
				$this->addFlash("Error", "Delete student failed");
				return $this->redirectToRoute("student_index");
			}
			$manager = $this->doctrine->getManager();
			$manager->remove($student);
			$manager->flush();
			$this->addFlash("Success", "Delete student succeed");
			return $this->redirectToRoute("student_index");
		}
}
