<?php declare(strict_types=1);

namespace App\Mechanism\Service;

use App\Mechanism\Entity\Activity;
use App\Mechanism\Entity\Badge;
use App\Mechanism\Entity\Location;
use App\Mechanism\Entity\Organization;

/**
 * Creates relations between collections, so the e.g. Organization will have getCity(), or getActivities() working
 */
class CollectionConnector
{
    private const MANY_TO_ONE = 'many';
    private const ONE_TO_ONE  = 'one';
    private const MAPPING = [
        Organization::class => [
            'location'   => [self::ONE_TO_ONE, Location::class, 'id'],
            'activities' => [self::MANY_TO_ONE, Activity::class, 'id'],
            'badges'     => [self::MANY_TO_ONE, Badge::class, 'id'],
        ],
    ];

    /**
     * Uses setter injection to inject dependencies between entities
     */
    public function process(): void
    {

    }
}
