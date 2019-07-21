<?php

namespace App\DataFixtures;

use Faker;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;

class FakerFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        // On configure dans quelles langues nous voulons nos données
        $faker = Faker\Factory::create('fr_FR');

        // on créé 30 articles
        for ($i = 0; $i < 30; $i++) {
            $article = new Article();
            $article->setTitle($faker->sentence());
            $article->setSmallDescription($faker->paragraph(1));
            $article->setLongDescription($faker->paragraph(20));
            $article->setThumbnail($faker->imageUrl());
            $article->setImageBigOne($faker->imageUrl());
        
            $manager->persist($article);
        }

        $manager->flush();
    }
}

?>