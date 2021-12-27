<?php

namespace App\DataFixtures;

use App\Entity\Teacher;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TeacherFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 10; $i++){
					$teacher = new Teacher();
					$teacher->setFullName("Teacher $i");
					$teacher->setAddress("Hanoi");
					$teacher->setMobile("0988777421");
					$teacher->setDob(\DateTime::createFromFormat('Y-m-d', '1999-11-12'));
					$teacher->setAvatar('default-avatar.png');
					$manager->persist($teacher);
				}
        $manager->flush();
    }
}
