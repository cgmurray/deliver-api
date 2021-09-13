<?php

namespace App\Repository;

use App\Entity\Banner;
use App\Entity\Polygon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Location\Distance\Haversine;
use Location\Line;
use Location\Polygon as LocationPolygon;
use Location\Coordinate;
use Location\Utility\PerpendicularDistance;

/**
 * @method Banner|null find($id, $lockMode = null, $lockVersion = null)
 * @method Banner|null findOneBy(array $criteria, array $orderBy = null)
 * @method Banner[]    findAll()
 * @method Banner[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BannerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Banner::class);
    }

    public function findInServiceArea(string $customerLat, string $customerLong, int $radiusFeet)
    {
        if (!is_numeric($customerLat) || !is_numeric($customerLong) || !is_int($radiusFeet)) {
            throw new \Exception('lat/long must be decimal string, and radius must be integer');
        }

        $customerPoint = new Coordinate($customerLat, $customerLong);
        $radiusMeters = $radiusFeet * 0.3048;
        $serviceAreaBanners = [];

        /** @var Banner[] $allBanners */
        $allBanners = $this->findAll();

        foreach ($allBanners as $banner) {
            /** @var Polygon[] $bannerPolygons */
            $bannerPolygons = $banner->getPolygons();

            foreach ($bannerPolygons as $polygonData) {
                $coordinateSetString = $polygonData->getPolygonCoordinates();
                $coordinateArray = explode('|', $coordinateSetString);
                $geofence = new LocationPolygon();

                foreach ($coordinateArray as $coordinatePairString) {
                    $coordinatePairString = trim($coordinatePairString);
                    $coordinatePairArray = explode(',', $coordinatePairString);
                    $lat = $coordinatePairArray[0];
                    $long = $coordinatePairArray[1];
                    $geofence->addPoint(new Coordinate($lat, $long));
                }

                /* If the customer is INSIDE the polygon, add this banner. */
                if ($geofence->contains($customerPoint)) {
                    $serviceAreaBanners[] = $banner;
                }
                /* else if the length of a perpendicular line to the line connecting the two closest points
                   on the polygon is less than the service area radius, add the banner. */
                else {
                    $distances = [];
                    foreach($geofence->getPoints() as $point) {
                        $line = new Line($customerPoint, $point);
                        $length = $line->getLength(new Haversine());
                        $distances[$length] = $point;
                    }

                    /* Use krsort (desc) so we can we can use pop instead of shift (faster) */
                    krsort($distances, SORT_NUMERIC);
                    $closestPoint1 = array_pop($distances);
                    $closestPoint2 = array_pop($distances);
                    $closestSegment = new Line($closestPoint1, $closestPoint2);

                    $perpendicularCalculator = new PerpendicularDistance();
                    $perpendicularDistance = $perpendicularCalculator->getPerpendicularDistance($customerPoint, $closestSegment);

                    if ($perpendicularDistance <= $radiusMeters) {
                        $serviceAreaBanners[] = $banner;
                    }
                }
            }
        }

        return $serviceAreaBanners;
    }
}
