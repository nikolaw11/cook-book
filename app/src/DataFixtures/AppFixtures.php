<?php

/**
 * This file is part of the cook-book project.
 *
 * (c) Your Name <your@email.com>
 */

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * Loads sample data into the database for development or testing.
 */
class AppFixtures extends Fixture
{
    /**
     * Load data fixtures with the passed ObjectManager.
     *
     * @param ObjectManager $manager the object manager for persisting entities
     */
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
