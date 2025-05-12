<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Band;
use App\Entity\Musician;
use App\Entity\Album;
use App\Entity\Song;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker\Factory;

class AppFixtures extends Fixture
{

    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
    ) {}

    public function load(ObjectManager $manager): void
    {
        // Create an admin user
        /*$admin = new User();
        $admin->setEmail('dark-shadow-95@windowslive.com');
        $admin->setUsername('Philip');
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'klausbotler1'));
        $admin->setRoles(['ROLE_ADMIN']);

        $manager->persist($admin);

        // Create a regular user
        $user = new User();
        $user->setEmail('user@example.com');
        $user->setUsername('UserTest');
        $user->setPassword($this->passwordHasher->hashPassword($user, 'userpass123'));
        $user->setRoles(['ROLE_USER']);

        $manager->persist($user);

        */
        /*
        $band1 = new Band();
        $band1->setName('Radiohead');
        $band1->setBio("Radiohead are an English rock band formed in Abingdon, Oxfordshire, in 1985. They comprise Thom Yorke (vocals, guitar, piano, keyboards); brothers Jonny Greenwood (guitar, keyboards, other instruments) and Colin Greenwood (bass); Ed O'Brien (guitar, backing vocals); and Philip Selway (drums, percussion).");
        $band1->setCoverImage('radiohead_cover.jpg');
        $band1->setLinks(['https://open.spotify.com/artist/4Z8W4fKeB5YxbusRsdQVPb?si=KY4fUNYwT6e2H2cFCgCIbw', 'https://www.instagram.com/radiohead/']);

*/

        $faker = Factory::create();

        $exampleNames = [
            ['name' => 'Thom Yorke', 'bio' => 'Lead vocalist of Radiohead', 'cover_image' => 'https://i.pravatar.cc/150?u=thom'],
            ['name' => 'Jonny Greenwood', 'bio' => 'Guitarist and composer', 'cover_image' => 'https://i.pravatar.cc/150?u=jonny'],
            ['name' => 'Colin Greenwood', 'bio' => 'Bassist of Radiohead', 'cover_image' => 'https://i.pravatar.cc/150?u=colin'],
            ['name' => 'Ed O\'Brien', 'bio' => 'Guitarist and backing vocals', 'cover_image' => 'https://i.pravatar.cc/150?u=ed'],
            ['name' => 'Philip Selway', 'bio' => 'Drummer of Radiohead', 'cover_image' => 'https://i.pravatar.cc/150?u=philip'],
        ];

        foreach ($exampleNames as $data) {
            $musician = new Musician();
            $musician->setName($data['name']);
            $musician->setBio($data['bio']);
            $musician->setCoverImage($data['cover_image']);
            $musician->setLinks([
                'instagram' => $faker->url(),
                'spotify' => $faker->url()
            ]);

            $manager->persist($musician);
        }
        /*
        // First, create the bands
        $band = new Band();
        $band->setName('Radiohead');
        $manager->persist($band);

        // Then, create musicians and relate them
        $musician = new Musician();
        $musician->setName('Thom Yorke');
        $musician->addBand($band); // <-- This sets band_id via the relation
*/
        $manager->flush();
    }
}
