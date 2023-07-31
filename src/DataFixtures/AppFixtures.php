<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Categorie;
use App\Entity\Commentaire;
use App\Entity\Team;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $faker;
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher){
        $this->passwordHasher = $passwordHasher;
        $this->faker = Factory::create('fr_FR');
    }
    public function load(ObjectManager $manager): void
    {
        $this->truncate($manager);
        $this->teamFixtures($manager);
        $this->userFixtures($manager);
        $this->categorieFixtures($manager);
        $this->articleFixtures($manager);
        $this->commentaireFixtures($manager);
    }

    protected function teamFixtures($manager) : void{
        $team = new Team;
        $team->setEmail('g.letalle@gmail.com');
        $hashedPassword = $this->passwordHasher->hashPassword(
            $team,
            'TrucdeOuf'
        );
        $team->setPassword($hashedPassword);
        $team->setRoles(['ROLE_ADMIN']);
        $team->setLastname('Letalle');
        $team->setFirstname('Guillaume');
        $manager->persist($team);

        $manager->flush();
    }

    protected function userFixtures($manager) : void{
        for($i=1; $i<=35; $i++){
            $user[$i] = new User;
            $user[$i]->setEmail('user'. $i.'@gmail.fr');
            $user[$i]->setFirstname($this->faker->firstName);
            $user[$i]->setLastname($this->faker->lastName);
            $user[$i]->setRoles(['ROLE_IDENTIFIED']);
            $hashedPassword = $this->passwordHasher->hashPassword(
                $user[$i],
                'TrucdeOuf');
            $user[$i]->setPassword($hashedPassword);
            $manager->persist($user[$i]);
        }
        $manager->flush();
    }

    protected function categorieFixtures($manager) : void{
        for($i=1; $i<=30;$i++){
            $categorie[$i] = new Categorie();
            $categorie[$i]->setName($this->faker->name);
            $manager->persist($categorie[$i]);
        }
        $manager->flush();
    }

    protected function articleFixtures($manager) : void{
        for($i=1; $i<=100; $i++){
            $article[$i] = new Article;
            $article[$i]->setContenu($this->faker->text(55));
            $article[$i]->setTitre($this->faker->word);
            $article[$i]->setDate($this->faker->dateTime());
            $article[$i]->setFkTeam($this->getReferencedObject(Team::class, 1, $manager));
            $article[$i]->setFkCategorie($this->getRandomReference('App\Entity\Categorie', $manager));
            $article[$i]->setImage('https://loremflickr.com/g/320/240/girl/all');
            $manager->persist($article[$i]);
        }
        $manager->flush();
    }

    protected function commentaireFixtures($manager) : void{
        for($i=1; $i<=500; $i++){
            $commentaire[$i] = new Commentaire();
            $commentaire[$i]->setContenu($this->faker->text(200));
            $commentaire[$i]->setDate($this->faker->dateTime());
            $commentaire[$i]->setFkUser($this->getRandomReference('App\Entity\User', $manager));
            $commentaire[$i]->setFkArticle($this->getRandomReference('App\Entity\Article', $manager));
            $manager->persist($commentaire[$i]);
        }
        $manager->flush();
    }

    protected function getReferencedObject(string $className, int $id, object $manager){
        return $manager->find($className, $id);
    }

    protected function getRandomReference(string $className, object $manager){
        $list = $manager->getRepository($className)->findAll();
        return $list[array_rand($list)];
    }

    protected  function truncate($manager) : void{
        // @var Connection db
        $db = $manager->getConnection();

        //start new transaction
        $db->beginTransaction();

        $sql ='
        SET FOREIGN_KEY_CHECKS = 0;
        TRUNCATE team;
        TRUNCATE user;
        TRUNCATE article;
        TRUNCATE commentaire;
        TRUNCATE categorie;
        SET FOREIGN_KEY_CHECKS=1;';

        $db->prepare($sql);
        $db->executeQuery($sql);

        $db->commit();
        $db->beginTransaction();
    }
}
