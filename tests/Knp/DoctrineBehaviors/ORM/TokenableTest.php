<?php

namespace Tests\Knp\DoctrineBehaviors\ORM;

use Knp\DoctrineBehaviors\Reflection\ClassAnalyzer;
use Doctrine\Common\EventManager;

require_once 'EntityManagerProvider.php';

class TokenableTest extends \PHPUnit_Framework_TestCase
{
    use EntityManagerProvider;

    protected function getUsedEntityFixtures()
    {
        return [
            'BehaviorFixtures\\ORM\\TokenableEntity',
            'BehaviorFixtures\\ORM\\TokenableUpdateEntity'
        ];
    }

    protected function getEventManager()
    {
        $em = new EventManager;

        $em->addEventSubscriber(
            new \Knp\DoctrineBehaviors\ORM\Tokenable\TokenableSubscriber(
                new ClassAnalyzer(),
                false,
                'Knp\DoctrineBehaviors\Model\Tokenable\Tokenable'
        ));

        return $em;
    }

    public function testTokenLoading()
    {
        $em = $this->getEntityManager();

        $entity = new \BehaviorFixtures\ORM\TokenableEntity();

        $entity->setTitle('Tokenable');

        $em->persist($entity);
        $em->flush();

        $this->assertNotNull($token = $entity->getToken());

        $em->clear();

        $entity = $em->getRepository('BehaviorFixtures\ORM\TokenableEntity')->findOneBy(array('token' => $token));

        $this->assertNotNull($entity);
    }

    public function testNotUpdatedToken()
    {
        $em = $this->getEntityManager();

        $entity = new \BehaviorFixtures\ORM\TokenableEntity();

        $em->persist($entity);
        $em->flush();

        $this->assertNotNull($entity->getToken());

        $expected = $entity->getToken();

        $entity->setTitle('Tokenable Update');

        $em->persist($entity);
        $em->flush();

        $this->assertEquals($expected, $entity->getToken());
    }

    public function testUpdatedToken()
    {
        $em = $this->getEntityManager();

        $entity = new \BehaviorFixtures\ORM\TokenableUpdateEntity();

        $entity->setTitle('Tokenable');

        $em->persist($entity);
        $em->flush();

        $this->assertNotNull($entity->getToken());

        $expected = $entity->getToken();

        $entity->setTitle('Tokenable No Update');

        $em->persist($entity);
        $em->flush();

        $this->assertNotEquals($expected, $entity->getToken());
    }
}
