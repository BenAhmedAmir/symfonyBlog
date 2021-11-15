<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;
use Faker;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('en_EN');
        for ($i = 1; $i <= 3; $i++) {
            $category = new Category();
            $category->setTitle($faker->title())
                ->setDescription($faker->paragraph());

            $manager->persist($category);
            for ($j = 1; $j <= mt_rand(4, 8); $j++) {
                $content = '<p>' . join($faker->paragraphs(5), '</p><p>') . '</p>';
                $article = new Article();
                $article->setTitle($faker->sentence())
                    ->setContent($content)
                    ->setImage($faker->imageUrl())
                    ->setCreateAt($faker->dateTimeBetween('-8 months'))
                    ->setCategory($category);
                //ici je vais dire a mon manager de persister mon article
                $manager->persist($article);


                for($k=1; $k<= mt_rand(4, 10); $k++){
                    $comment = new Comment();
                    $content = '<p>' . join($faker->paragraphs(3), '</p><p>') . '</p>';
                    $days = (new \DateTime())->diff($article->getCreateAt())->days;
                    $comment->setAuthor($faker->name)
                            ->setContent($content)
                            ->setCreatedAt($faker->dateTimeBetween('-' . $days . 'days'))
                            ->setArticle($article);
                    $manager->persist($comment);

                }
            }
            // pour balancer reelement la req sql que jai fais ici
            $manager->flush();
        }
    }

}
