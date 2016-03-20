<?php
/**
 * Created by PhpStorm.
 * User: Tomek
 * Date: 20.03.2016
 * Time: 16:56
 */

namespace UserBundle\Security\User;

use Fp\OpenIdBundle\Model\UserManager;
use Fp\OpenIdBundle\Model\IdentityManagerInterface;
use Doctrine\ORM\EntityManager;
use UserBundle\Entity\OpenIdIdentity;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;


use Symfony\Component\Security\Core\Exception\InsufficientAuthenticationException;
use UserBundle\Entity\User;

class OpenIdUserManager extends UserManager
{
    // we will use an EntityManager, so inject it via constructor
    public function __construct(IdentityManagerInterface $identityManager, EntityManager $entityManager)
    {
        parent::__construct($identityManager);
        $this->entityManager = $entityManager;
    }

    /**
     * @param string $identity
     *  an OpenID token. With Google it looks like:
     *  https://www.google.com/accounts/o8/id?id=SOME_RANDOM_USER_ID
     * @param array $attributes
     *  requested attributes (explained later). At the moment just
     *  assume there's a 'contact/email' key

     */
    public function createUserFromIdentity($identity, array $attributes = array())
    {

        // in this example, we fetch User entities by e-mail
        $user = $this->entityManager->getRepository('UserBundle:User')->findOneBy(array(
            'steamid' => $identity
        ));


        if (null === $user) {
            $user = new User();
            $this->entityManager->persist($user);
            $this->entityManager->flush();
//            throw new BadCredentialsException('No corresponding user!');
        }




        // we create an OpenIdIdentity for this User
        $openIdIdentity = new OpenIdIdentity();
        $openIdIdentity->setIdentity($identity);
        $openIdIdentity->setAttributes($attributes);
        $openIdIdentity->setUser($user);

        $this->entityManager->persist($openIdIdentity);
        $this->entityManager->flush();

        // end of example

        return $user; // you must return an UserInterface instance (or throw an exception)
    }
}