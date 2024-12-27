<?php

namespace App\DataFixtures;

use App\Entity\Folder;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * This class loads the default data into the database, in this case, only the default folder.
 * https://symfony.com/bundles/DoctrineFixturesBundle/current/index.html
 */
class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $folder = new Folder();
        $folder->setName("My notes");
        $manager->persist($folder);
        $manager->flush();
    }
}
