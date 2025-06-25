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
use Symfony\Component\String\Slugger\SluggerInterface;
use Doctrine\ORM\EntityManagerInterface;

class AppFixtures extends Fixture
{

    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
        private SluggerInterface $slugger,
        private EntityManagerInterface $em
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
        /*
        $faker = Factory::create();

        $exampleNames = [

            // Massive Attack
            [
                'name' => 'Robert "3D" Del Naja',
                'bio' => 'Vocalist and producer of Massive Attack',
                'cover_image' => 'robert_3d_del_naja_cover.jpg',
                'links' => [
                    'instagram' => 'https://www.instagram.com/robert3delnaja/',
                    'spotify' => 'https://open.spotify.com/artist/42ZwlJORn9OKo15UYAgIFa'
                ]
            ],
            [
                'name' => 'Grant "Daddy G" Marshall',
                'bio' => 'Vocalist and producer of Massive Attack',
                'cover_image' => 'grant_daddyg_marshall_cover.jpg',
                'links' => [
                    'instagram' => 'https://www.instagram.com/daddygofficial/',
                    'spotify' => 'https://open.spotify.com/artist/6FXMGgJwohJLUSr5nVlf9X'
                ]
            ]

        ];

        foreach ($exampleNames as $data) {
            $musician = new Musician();
            $musician->setName($data['name']);
            $musician->setBio($data['bio']);
            $musician->setCoverImage($data['cover_image']);
            $musician->setLinks($data['links']);

            $slug = $this->slugger->slug($data['name'])->lower();
            $musician->setSlug($slug);

            $manager->persist($musician);
        }
        */
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
        /*
        $band = $this->em->getRepository(Band::class)->findOneBy(['id' => '5']);
        $musician = $this->em->getRepository(Musician::class)->findOneBy(['id' => '26']);
        $musician->addBand($band);
        $band->addMusician($musician);

        $manager->persist($band);
        // set other musician fields...
        $manager->persist($musician);
*/
        /*
$musician = $this->em->getRepository(Musician::class)->findOneBy(['id' => '11']);

if ($musician) {
    $musician->setLinks([
        'instagram' => 'https://www.instagram.com/willhuntofficial',
        'spotify' => 'https://open.spotify.com/artist/5nGIFgo0shDenQYSE0Sn7c',
    ]);

    $manager->persist($musician);
}
*/

        /*
        $band11 = new Band();
        $band11->setName('Madrugada');
        $band11->setBio("Madrugada is a Norwegian alternative rock band formed in Stokmarknes in 1993, with a core lineup of Sivert Høyem (vocals), Robert Burås (guitar), and Frode Jacobsen (bass). Following Burås' death on 12 July 2007, Høyem and Jacobsen decided to finish recording what was to be their final album in the original lineup. On 21 January 2008, the band released Madrugada and announced that they would split after one last tour. They performed their final concert on 15 November 2008.");
        $band11->setCoverImage('madrugada_cover.jpg');
        $band11->setLinks(['https://open.spotify.com/artist/0iC8O5ABswVUFiYwM94bu3', 'https://www.instagram.com/madrugadamusic/']);

        $slug = $this->slugger->slug('Madrugada')->lower();
        $band11->setSlug($slug);

        $manager->persist($band11);

        $band12 = new Band();
        $band12->setName('System Of A Down');
        $band12->setBio("System of a Down is an Armenian-American heavy metal band formed in Glendale, California, in 1994. Since 1997, the band has consisted of founding members Serj Tankian (lead vocals, keyboards), Daron Malakian (guitar, vocals), and Shavo Odadjian (bass, backing vocals); along with John Dolmayan (drums), who replaced original drummer Andy Khachaturian.");
        $band12->setCoverImage('system_of_a_down_cover.jpg');
        $band12->setLinks(['https://open.spotify.com/artist/5eAWCfyUhZtHHtBdNk56l1', 'https://www.instagram.com/systemofadown/']);

        $slug = $this->slugger->slug('System Of A Down')->lower();
        $band12->setSlug($slug);

        $manager->persist($band12);

        $band13 = new Band();
        $band13->setName('Rammstein');
        $band13->setBio("Rammstein is a German Neue Deutsche Härte band formed in Berlin in 1994. The band's lineup—consisting of lead vocalist Till Lindemann, lead guitarist Richard Kruspe, rhythm guitarist Paul Landers, bassist Oliver Riedel, drummer Christoph Schneider, and keyboardist Christian \"Flake\" Lorenz—has remained unchanged throughout their history, along with their approach to songwriting, which consists of Lindemann writing and singing the lyrics over instrumental pieces the rest of the band has completed beforehand. Prior to their formation, some members were associated with the punk rock acts Feeling B and First Arsch.");
        $band13->setCoverImage('rammstein_cover.jpg');
        $band13->setLinks(['https://open.spotify.com/artist/6wWVKhxIU2cEi0K81v7HvP', 'https://www.instagram.com/rammsteinofficial/']);

        $slug = $this->slugger->slug('Rammstein')->lower();
        $band13->setSlug($slug);
*/
        /*

        $exampleNames = [
            // Radiohead
            [
                'title' => 'The Bends',
                'genre' => 'Alternative',
                'duration' => '2913',
                'release_date' => '13 March 1995',
                'description' => 'The Bends is the second studio album by the English rock band Radiohead, released on 13 March 1995 by Parlophone. It was produced by John Leckie, with extra production by Radiohead, Nigel Godrich and Jim Warren. The Bends combines guitar songs and ballads, with more restrained arrangements and cryptic lyrics than Radiohead\'s debut album, Pablo Honey (1993).',
                'cover_image' => 'the_bends_cover.jpg',
                'links' => [
                    'spotify' => 'https://open.spotify.com/artist/35UJLpClj5EDrhpNIi4DFg',
                    'soundcloud' => 'https://soundcloud.com/radiohead/the-bends'
                ]
            ]
        ];

        foreach ($exampleNames as $data) {
            $album = new Album();
            $album->setTitle($data['title']);
            $album->setGenre($data['genre']);
            $album->setReleaseDate(new DateTimeImmutable($data['release_date']));
            $album->setDuration($data['duration']);
            $album->setDescription($data['description']);
            $album->setCoverImage($data['cover_image']);
            $album->setLinks($data['links']);

            $slug = $this->slugger->slug($data['title'])->lower();
            $album->setSlug($slug);

            $manager->persist($album);
        }
*/

        /*
        // Album to band, Band to album
        $band = $this->em->getRepository(Band::class)->findOneBy(['id' => '1']);
        $album = $this->em->getRepository(Album::class)->findOneBy(['id' => '1']);

        $album->addBand($band);
        $band->addAlbum($album);

        $manager->persist($band);

        $manager->persist($album);
*/
        /*
        // Album to musician, Musician to album
        $musician = $this->em->getRepository(Musician::class)->findOneBy(['id' => '1']);
        $album = $this->em->getRepository(Album::class)->findOneBy(['id' => '1']);

        $album->addMusician($musician);
        $musician->addAlbum($album);

        $manager->persist($musician);

        $manager->persist($album);
        */
        /*
        // Song to album, Album to song
        $album = $this->em->getRepository(Album::class)->findOneBy(['id' => '1']);
        $song = $this->em->getRepository(Song::class)->findOneBy(['id' => '1']);

        $album->addSong($song);
        $song->addAlbum($song);

        $manager->persist($album);

        $manager->persist($song);
*/
        /*
        // Song to Musician, Musician to song
        $musician = $this->em->getRepository(Musician::class)->findOneBy(['id' => '1']);
        $song = $this->em->getRepository(Song::class)->findOneBy(['id' => '1']);

        $musician->addSong($song);
        $song->addMusician($song);

        $manager->persist($musician);

        $manager->persist($song);
*/
        /*
        $exampleNames = [
            // Radiohead
            [
                'title' => 'My Iron Lung',
                'genre' => 'Alternative rock',
                'duration' => '261',
                'release_date' => '26 September 1994',
                'description' => 'Radiohead wrote "My Iron Lung" in response to the success of their debut single, "Creep" (1992). Unsatisfied with the version recorded at RAK Studios, they used an edited performance recorded in May 1994 at the London Astoria. "My Iron Lung" was  released as both a single and an album, and reached number 24 on the UK singles chart.',
                'cover_image' => 'my_iron_lung.jpg',
                'links' => [
                    'spotify' => 'https://open.spotify.com/artist/0jyikFM0Umv0KlnrOEKtTG',
                    'soundcloud' => 'https://soundcloud.com/radiohead/my-iron-lung'
                ]
            ]
        ];

        foreach ($exampleNames as $data) {
            $song = new Song();
            $song->setTitle($data['title']);
            $song->setGenre($data['genre']);
            $song->setReleaseDate(new DateTimeImmutable($data['release_date']));
            $song->setDuration($data['duration']);
            $song->setDescription($data['description']);
            $song->setCoverImage($data['cover_image']);
            $song->setLinks($data['links']);

            $slug = $this->slugger->slug($data['title'])->lower();
            $song->setSlug($slug);

            $manager->persist($song);
        }
        */
        $exampleNames = [
            // Radiohead
            /*[
                'title' => 'Planet Telex',
                'genre' => 'Alternative rock',
                'duration' => '250',
                'release_date' => '27 February 1995',
                'description' => '"Planet Telex" developed from studio experimentation with drum loops. It was released as an A-side single on 27 February 1995 by Parlophone and Capitol Records.',
                'cover_image' => 'planet_telex_cover.jpg',
                'links' => [
                    'spotify' => 'https://open.spotify.com/artist/37JISltgxizbDAyNEEqkTY',
                    'soundcloud' => 'https://soundcloud.com/radiohead/planet-telex'
                ]
            ],*/
            [
                'title' => 'The Bends',
                'genre' => 'Alternative rock',
                'duration' => '243',
                'release_date' => '26 July 1996',
                'description' => '"The Bends" is a song by the English rock band Radiohead from their second studio album, The Bends (1995). In Ireland, it was released by Parlophone on 26 July 1996 as the album\'s sixth and final single, reaching number 26 on the Irish Singles Chart.',
                'cover_image' => 'the_bends_cover.jpg',
                'links' => [
                    'spotify' => 'https://open.spotify.com/artist/7oDFvnqXkXuiZa1sACXobj',
                    'soundcloud' => 'https://soundcloud.com/radiohead/the-bends'
                ]
            ],
            [
                'title' => 'High and Dry',
                'genre' => 'Alternative rock',
                'duration' => '250',
                'release_date' => '27 February 1995',
                'description' => 'High and Dry is a song by the English rock band Radiohead, released on their second album, The Bends (1995). "High and Dry" was recorded as a demo during the sessions for Radiohead\'s first album, Pablo Honey (1993), and remastered for The Bends. It is credited as an influence on the bands Travis and Coldplay. Two music videos were produced for "High and Dry".',
                'cover_image' => 'high_and_dry_cover.jpg',
                'links' => [
                    'spotify' => 'https://open.spotify.com/artist/2a1iMaoWQ5MnvLFBDv4qkf',
                    'soundcloud' => 'https://soundcloud.com/radiohead/high-and-dry'
                ]
            ],
            [
                'title' => 'Fake Plastic Trees',
                'genre' => 'Alternative rock',
                'duration' => '270',
                'release_date' => '15 May 1995',
                'description' => '"Fake Plastic Trees" is a song by the English rock band Radiohead, released in May 1995 by Parlophone from their second album, The Bends (1995). It was the third single from The Bends in the UK, and the first in the US.',
                'cover_image' => 'fake_plastic_trees_cover.jpg',
                'links' => [
                    'spotify' => 'https://open.spotify.com/artist/73CKjW3vsUXRpy3NnX4H7F',
                    'soundcloud' => 'https://soundcloud.com/radiohead/fake-plastic-trees'
                ]
            ],
            [
                'title' => 'Bones',
                'genre' => 'Alternative rock',
                'duration' => '185',
                'release_date' => '27 February 1995',
                'description' => '"Bones" is a song by the English rock band Radiohead from their second studio album, The Bends (1995).',
                'cover_image' => 'bones_cover.jpg',
                'links' => [
                    'spotify' => 'https://open.spotify.com/artist/76RAlQcfuQknnQFruYDj6Q',
                    'soundcloud' => 'https://soundcloud.com/radiohead/bones'
                ]
            ],
            [
                'title' => '(Nice Dream)',
                'genre' => 'Alternative rock',
                'duration' => '212',
                'release_date' => '27 February 1995',
                'description' => '"(Nice Dream)" is a song by the English rock band Radiohead from their second studio album, The Bends (1995).',
                'cover_image' => 'nice_dream_cover.jpg',
                'links' => [
                    'spotify' => 'https://open.spotify.com/artist/1tZcw7GtIqviL32bzaKdSo',
                    'soundcloud' => 'https://soundcloud.com/radiohead/nice-dream'
                ]
            ],
            /*
            [
                'title' => 'Just',
                'genre' => 'Alternative rock',
                'duration' => '213',
                'release_date' => '27 February 1995',
                'description' => '"The Bends" is a song by the English rock band Radiohead from their second studio album, The Bends (1995). In Ireland, it was released by Parlophone on 26 July 1996 as the album\'s sixth and final single, reaching number 26 on the Irish Singles Chart.',
                'cover_image' => 'just_cover.jpg',
                'links' => [
                    'spotify' => 'https://open.spotify.com/artist/37JISltgxizbDAyNEEqkTY',
                    'soundcloud' => 'https://soundcloud.com/radiohead/plane'
                ]
            ],
            [
                'title' => 'My Iron Lung',
                'genre' => 'Alternative rock',
                'duration' => '261',
                'release_date' => '26 September 1994',
                'description' => 'Radiohead wrote "My Iron Lung" in response to the success of their debut single, "Creep" (1992). Unsatisfied with the version recorded at RAK Studios, they used an edited performance recorded in May 1994 at the London Astoria. "My Iron Lung" was  released as both a single and an album, and reached number 24 on the UK singles chart.',
                'cover_image' => 'my_iron_lung_cover.jpg',
                'links' => [
                    'spotify' => 'https://open.spotify.com/artist/0jyikFM0Umv0KlnrOEKtTG',
                    'soundcloud' => 'https://soundcloud.com/radiohead/my-iron-lung'
                ]
            ],
            [
                'title' => 'Bullet Proof..I Wish I Was',
                'genre' => 'Alternative rock',
                'duration' => '197',
                'release_date' => '26 September 1994',
                'description' => 'Radiohead wrote "My Iron Lung" in response to the success of their debut single, "Creep" (1992). Unsatisfied with the version recorded at RAK Studios, they used an edited performance recorded in May 1994 at the London Astoria. "My Iron Lung" was  released as both a single and an album, and reached number 24 on the UK singles chart.',
                'cover_image' => 'bullet_proof_i_wish_i_was_cover.jpg',
                'links' => [
                    'spotify' => 'https://open.spotify.com/artist/0jyikFM0Umv0KlnrOEKtTG',
                    'soundcloud' => 'https://soundcloud.com/radiohead/myiiii'
                ]
            ],
            [
                'title' => 'Black Star',
                'genre' => 'Alternative rock',
                'duration' => '244',
                'release_date' => '27 February 1995',
                'description' => '"The Bends" is a song by the English rock band Radiohead from their second studio album, The Bends (1995). In Ireland, it was released by Parlophone on 26 July 1996 as the album\'s sixth and final single, reaching number 26 on the Irish Singles Chart.',
                'cover_image' => 'black_star_cover.jpg',
                'links' => [
                    'spotify' => 'https://open.spotify.com/artist/37JISltgxizbDAyNEEqkTY',
                    'soundcloud' => 'https://soundcloud.com/radiohead/planetex'
                ]
            ],
            [
                'title' => 'Sulk',
                'genre' => 'Alternative rock',
                'duration' => '208',
                'release_date' => '27 February 1995',
                'description' => '"The Bends" is a song by the English rock band Radiohead from their second studio album, The Bends (1995). In Ireland, it was released by Parlophone on 26 July 1996 as the album\'s sixth and final single, reaching number 26 on the Irish Singles Chart.',
                'cover_image' => 'sulk_cover.jpg',
                'links' => [
                    'spotify' => 'https://open.spotify.com/artist/37JISltgxizbDAyNEEqkTY',
                    'soundcloud' => 'https://soundcloud.com/radiohead/planet'
                ]
            ],
            [
                'title' => 'Street Spirit (Fade Out)',
                'genre' => 'Alternative rock',
                'duration' => '248',
                'release_date' => '27 February 1995',
                'description' => '"The Bends" is a song by the English rock band Radiohead from their second studio album, The Bends (1995). In Ireland, it was released by Parlophone on 26 July 1996 as the album\'s sixth and final single, reaching number 26 on the Irish Singles Chart.',
                'cover_image' => 'street_spirit_cover.jpg',
                'links' => [
                    'spotify' => 'https://open.spotify.com/artist/37JISltgxizbDAyNEEqkTY',
                    'soundcloud' => 'https://soundcloud.com/radiohead/plane'
                ]
            ],
            */
        ];

        foreach ($exampleNames as $data) {
            $song = new Song();
            $song->setTitle($data['title']);
            $song->setGenre($data['genre']);
            $song->setReleaseDate(new DateTimeImmutable($data['release_date']));
            $song->setDuration($data['duration']);
            $song->setDescription($data['description']);
            $song->setCoverImage($data['cover_image']);
            $song->setLinks($data['links']);

            $slug = $this->slugger->slug($data['title'])->lower();
            $song->setSlug($slug);

            $manager->persist($song);
        }
        /*
        // Song to album, Album to song
        $album = $this->em->getRepository(Album::class)->findOneBy(['id' => '1']);
        $song = $this->em->getRepository(Song::class)->findOneBy(['id' => '1']);

        $album->addSong($song);
        $song->addAlbum($album);

        $manager->persist($album);

        $manager->persist($song);
*/
        $manager->flush();
    }
}
