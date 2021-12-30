<?php

namespace App\Controller;

use App\Entity\Classroom;
use App\Form\ClassroomType;
use Doctrine\Persistence\ManagerRegistry;
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
	 * @Route ("/classroom/add_student/{id}", name="classroom_addStudent")
	 */
	public function addStudent($id): Response
	{
		$class_student = $this->doctrine->getRepository(Classroom::class)->find($id);
		dd($class_student);
		return $this->render('classroom/add_student.html.twig');
	}
}
