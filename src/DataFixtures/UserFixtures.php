<?php

namespace App\DataFixtures;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\User;

class UserFixtures extends Fixture
{
     private $passwordEncoder;

     public function __construct(UserPasswordEncoderInterface $passwordEncoder)
     {
         $this->passwordEncoder = $passwordEncoder;
     }
    public function load(ObjectManager $manager)
    {
         $user = new User();
         $user->setNickname("So");
         $user->setEmail("so@so.so");
         $roles[] = 'ROLE_USER';
         $user->setRoles($roles);
         $user->setPassword($this->passwordEncoder->encodePassword(
             $user,
             'the_new_password'
         ));
         $manager->persist($user);
         $manager->flush();
    }
}
