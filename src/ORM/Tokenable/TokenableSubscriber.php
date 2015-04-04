<?php
/**
 * @author CkDeveloper
 * Freely released with no restrictions, re-license however you'd like!
 */

namespace Knp\DoctrineBehaviors\ORM\Tokenable;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Knp\DoctrineBehaviors\Reflection\ClassAnalyzer;

use Knp\DoctrineBehaviors\ORM\AbstractSubscriber;

use Doctrine\ORM\Event\LoadClassMetadataEventArgs,
    Doctrine\Common\EventSubscriber,
    Doctrine\ORM\Events,
    Doctrine\ORM\Mapping\ClassMetadata;

/**
 * Tokenable subscriber.
 *
 * Adds mapping to tokenable entities.
 */
class TokenableSubscriber extends AbstractSubscriber
{
    private $tokenableTrait;

    public function __construct(ClassAnalyzer $classAnalyzer, $isRecursive, $tokenableTrait)
    {
        parent::__construct($classAnalyzer, $isRecursive);

        $this->tokenableTrait = $tokenableTrait;
    }

    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        $classMetadata = $eventArgs->getClassMetadata();

        if (null === $classMetadata->reflClass) {
            return;
        }

        if ($this->isTokenable($classMetadata)) {
            if (!$classMetadata->hasField('token')) {
                $classMetadata->mapField(array(
                    'fieldName' => 'token',
                    'type'      => 'string',
                    'nullable'  => false
                ));
            }
        }
    }

    public function prePersist(LifecycleEventArgs $eventArgs)
    {
        $entity = $eventArgs->getEntity();
        $em = $eventArgs->getEntityManager();
        $classMetadata = $em->getClassMetadata(get_class($entity));

        if ($this->isTokenable($classMetadata)) {
            $entity->generateToken();
        }
    }

    public function preUpdate(LifecycleEventArgs $eventArgs)
    {
        $entity = $eventArgs->getEntity();
        $em = $eventArgs->getEntityManager();
        $classMetadata = $em->getClassMetadata(get_class($entity));

        if ($this->isTokenable($classMetadata)) {
            $entity->generateToken();
        }
    }

    public function getSubscribedEvents()
    {
        return [ Events::loadClassMetadata, Events::prePersist, Events::preUpdate ];
    }

    /**
     * Checks if entity is tokenable
     *
     * @param ClassMetadata $classMetadata The metadata
     *
     * @return boolean
     */
    private function isTokenable(ClassMetadata $classMetadata)
    {
        return $this->getClassAnalyzer()->hasTrait(
            $classMetadata->reflClass,
            $this->tokenableTrait,
            $this->isRecursive
        );
    }
}
