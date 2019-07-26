<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use App\Entity\Post;
use App\Entity\User;

class AppFixtures extends Fixture
{   
    private $passwordEncoder;
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $this->userLoad($manager);
        $this->postLoad($manager);
    }


    public function postLoad(ObjectManager $manager){
        $post = new Post();
        $post->setTitle('my title');
        $post->setSlug('my-slug-');
        $post->setPublished(new \DateTime());
        $post->setContent('my-content');

        $user = $this->getReference('user_admin');
        $post->setAuthor($user);

        $manager->persist($post);
        $manager->flush();
    }


    public function userLoad(ObjectManager $manager){
        $user = new User();

        $user->setUsername('admin');
        $user->setName('Mohamed CHIBANI');
        $user->setEmail('admin@chibani.com');
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'secret123'));

        $this->addReference('user_admin', $user);

        $manager->persist($user);
        $manager->flush();

    }

    public function commentLoad(){}
}
