<?php declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use App\Mechanism\Entity\Activity;
use App\Mechanism\Entity\Badge;
use App\Mechanism\Entity\Organization;
use App\Mechanism\Entity\Location;
use App\Mechanism\Service\CollectionFactory;
use TightenCo\Jigsaw\Collection\CollectionItem;

return [
    'build' => [
        'source'      => 'content',
        'destination' => 'build_local',
    ],
    'production' => false,
    'baseUrl' => '',
    'title' => 'Federation Card',
    'description' => 'Website description.',


    /**
     * 1. Collect all files
     * 2. Create models e.g. "Activity" or "Organization"
     * 3. After all collections are bootstrapped by framework, then our "CollectionConnector" will join them together
     *    to create relations
     */
    'collections' => [
        'organizations' => [
            'path' => 'organizations/{location}/{url}',
            'sort' => 'name',
            'items' => (new CollectionFactory('organizations'))->discover(),
            'map' => function (CollectionItem $item) {
                return Organization::fromItem($item);
            }
        ],
        'activities' => [
            'path' => 'activities/{id}',
            'sort' => 'name',
            'items' => (new CollectionFactory('activities'))->discover(),
            'map' => function (CollectionItem $item) {
                return Activity::fromItem($item);
            }
        ],
        'badges' => [
            'path' => 'organization-types/{id}',
            'sort' => 'name',
            'items' => (new CollectionFactory('badges'))->discover(),
            'map' => function (CollectionItem $item) {
                return Badge::fromItem($item);
            }
        ],
        'location' => [
            'path' => 'location/{id}',
            'sort' => 'name',
            'items' => (new CollectionFactory('locations'))->discover(),
            'map' => function (CollectionItem $item) {
                return Location::fromItem($item);
            }
        ]
    ],
];
