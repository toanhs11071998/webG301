<?php

namespace App\DataFixtures;

use App\Entity\Student;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class StudentFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
				for ($i = 0; $i < 10; $i ++){
					$student = new Student();
					$student->setFullName("Student $i");
					$student->setAddress("Hanoi");
					$student->setMobile('0988777555');
					$student->setDob(\DateTime::createFromFormat('Y-m-d', '2000-07-11'));
					$student->setAvatar('default-avatar.png');
					$manager->persist($student);
				}
        $manager->flush();
    }
}
