<?php

namespace App\Controller;

use App\Entity\Classroom;
use App\Entity\Student;
use App\Form\ClassroomType;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClassroomController extends AbstractController
{
	public function __construct(ManagerRegistry $doctrine)
	{
		$this->doctrine = $doctrine;
	}

	/**
	 * @Route ("/classroom", name="classroom_index")
	 */
	public function index(): Response
	{
		$classrooms = $this->doctrine->getRepository(Classroom::class)->findAll();
		return $this->render('classroom/index.html.twig', [
				'classrooms' => $classrooms
		]);
	}

	/**
	 * @IsGranted("ROLE_ADMIN")
	 * @Route ("/classroom/create", name="classroom_create")
	 */
	public function create(Request $request): Response
	{
		$classroom = new Classroom();
		$form = $this->createForm(ClassroomType::class, $classroom);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$manager = $this->doctrine->getManager();
			$manager->persist($classroom);
			$manager->flush();
			$this->addFlash('Success', "Add Classroom Succeeded");
			return $this->redirectToRoute('classroom_index');
		}
		return $this->renderForm('classroom/create.html.twig',
				[
						'form' => $form
				]);
	}

	/**
	 * @IsGranted("ROLE_ADMIN")
	 * @Route ("/classroom/edit/{id}", name="classroom_edit")
	 */
	public function edit(Request $request, $id): \Symfony\Component\HttpFoundation\RedirectResponse|Response
	{
		$classroom = $this->doctrine->getRepository(Classroom::class)->find($id);
		if (!$classroom) {
			$this->addFlash("Error", "Classroom Not Found");
			return $this->redirectToRoute("classroom_index");
		}

		$form = $this->createForm(ClassroomType::class, $classroom);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$manager = $this->doctrine->getManager();
			$manager->persist($classroom);
			$manager->flush();
			$this->addFlash('Success', "Edit Classroom Succeeded");
			return $this->redirectToRoute('classroom_index');
		}

		return $this->renderForm('classroom/edit.html.twig', [
				"form" => $form
		]);
	}

	/**
	 * @IsGranted("ROLE_ADMIN")
	 * @Route ("/classroom/delete/{id}", name="classroom_delete")
	 */
	public function delete($id): \Symfony\Component\HttpFoundation\RedirectResponse
	{
		$classroom = $this->doctrine->getRepository(Classroom::class)->find($id);
		if (!$classroom) {
			$this->addFlash("Error", "Delete classroom failed");
			return $this->redirectToRoute("classroom_index");
		}
		$manager = $this->doctrine->getManager();
		$manager->remove($classroom);
		$manager->flush();
		$this->addFlash("Success", "Delete classroom succeed");
		return $this->redirectToRoute("classroom_index");
	}

	/**
	 * @IsGranted("ROLE_ADMIN")
	 * @Route ("/classroom/{id}", name="classroom_addStudent")
	 */
	public function addStudent($id): Response
	{
		$students = $this->doctrine->getRepository(Student::class)->findAll();
		$class_student = $this->doctrine->getRepository(Classroom::class)->find($id);
		if (!$class_student) {
			$this->addFlash("Error", "Classroom Not Found");
			return $this->redirectToRoute("classroom_index");
		}

		$signed_id = [];
		foreach ($class_student->getStudents()->toArray() as $student){
			array_push($signed_id, $student->getId());
		}
		return $this->render('classroom/add_student.html.twig',
		[
				'students' => $students,
				'class_student' => $class_student,
				'signed_id' => $signed_id,
		]);
	}

	/**
	 * @IsGranted("ROLE_ADMIN")
	 * @Route ("/classroom/{class_id}/sign_student/{student_id}", name="classroom_signStudent")
	 */
	public function signStudent($class_id, $student_id): Response
	{
		$student = $this->doctrine->getRepository(Student::class)->find($student_id);
		$class = $this->doctrine->getRepository(Classroom::class)->find($class_id);

		$manager = $this->doctrine->getManager();
		$class_student = $class->addStudent($student);

		if ($class_student){
			$manager->persist($class_student);
			$manager->flush();
			$this->addFlash("Success", "Sign student succeed");
		}else {
			$this->addFlash("Error", "Sign student failed");
		}
		return $this->redirectToRoute('classroom_addStudent', [
				'id' => $class_id
		]);
	}

	/**
	 * @IsGranted("ROLE_ADMIN")
	 * @Route ("/classroom/{class_id}/remove_student/{student_id}", name="classroom_removeStudent")
	 */
	public function removeStudent($class_id, $student_id): Response
	{
		$student = $this->doctrine->getRepository(Student::class)->find($student_id);
		$class = $this->doctrine->getRepository(Classroom::class)->find($class_id);

		$manager = $this->doctrine->getManager();
		$class_student = $class->removeStudent($student);

		if ($class_student){
			$manager->persist($class_student);
			$manager->flush();
			$this->addFlash("Success", "Remove student succeed");
		}else {
			$this->addFlash("Error", "Remove student failed");
		}
		return $this->redirectToRoute('classroom_addStudent', [
				'id' => $class_id
		]);
	}
}
