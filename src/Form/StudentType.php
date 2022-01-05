<?php

namespace App\Form;

use App\Entity\Student;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StudentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('full_name', TextType::class,
						[
								'label' => 'Student Full Name',
								'required' => true,
								'attr' => [
										'maxlength' => 100,
										'minlength' => 5,
								]
						])
            ->add('dob', DateType::class,
						[
								'label' => "Date of birth",
								'required' => true,
								'widget' => 'single_text'
						])
            ->add('mobile', TextType::class,
						[
								'label' => 'Student Mobile',
								'required' => true,
								'attr' => [
										'maxlength' => 10,
										'minlength' => 10,
								]
						])
            ->add('address', TextType::class,
						[
								'label' => 'Student Address',
								'required' => true,
								'attr' => [
										'maxlength' => 100,
										'minlength' => 5,
								]
						])
            ->add('avatar', FileType::class,
						[
								'label' => "Student Avatar",
								'data_class' => null,
								'required' => is_null($builder->getData()->getAvatar()),
						])
//            ->add('classrooms')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Student::class,
        ]);
    }
}
