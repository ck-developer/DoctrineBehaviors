<?php

namespace Knp\DoctrineBehaviors\ORM\Geocodable;

use Knp\DoctrineBehaviors\ORM\Geocodable\Type\Point;

trait GeocodableRepository
{
    public function findByDistanceQB(Point $point, $distanceMax)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('DISTANCE(e.location, :location) <= :distanceMax')
            ->setParameter('location', $point)
            ->setParameter('distanceMax', $distanceMax)
        ;
    }

    public function findByDistance(Point $point, $distanceMax)
    {
        return $this->findByDistanceQB($point, $distanceMax)
            ->getQuery()
            ->execute()
        ;
    }

    public function findByDistanceInMetersQB(Point $point, $distanceMax)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('DISTANCE_IN_METERS(e.location, :latitude, :longitude) <= :distanceMax')
            ->setParameter('latitude', $point->getLatitude())
            ->setParameter('longitude', $point->getLongitude())
            ->setParameter('distanceMax', $distanceMax)
        ;
    }

    public function findByDistanceInMeters(Point $point, $distanceMax)
    {
        return $this->findByDistanceInMetersQB($point, $distanceMax)
            ->getQuery()
            ->execute()
        ;
    }
}

