<?php declare(strict_types=1);

namespace App\Mechanism\Service;

use App\Mechanism\Entity\Activity;
use App\Mechanism\Entity\Badge;
use App\Mechanism\Entity\FederationCardEntity;
use App\Mechanism\Entity\Location;
use App\Mechanism\Entity\Organization;
use Exception;
use TightenCo\Jigsaw\Collection\CollectionItem;
use TightenCo\Jigsaw\PageVariable;
use TightenCo\Jigsaw\SiteData;

/**
 * Creates relations between collections, so the e.g. Organization will have getCity(), or getActivities() working
 */
class CollectionConnector
{
    private const MANY_TO_ONE = 'many';
    private const ONE_TO_ONE  = 'one';
    private const MAPPING = [
        Organization::class => [
            'location'  =>  ['type' => self::ONE_TO_ONE,  'class' => Location::class, 'field' => 'id'],
            'activities' => ['type' => self::MANY_TO_ONE, 'class' => Activity::class, 'field' => 'id'],
            'badges'     => ['type' => self::MANY_TO_ONE, 'class' => Badge::class,    'field' => 'id'],
        ],
    ];

    /**
     * Uses setter injection to inject dependencies between entities
     */
    public static function process(SiteData $collections): void
    {
        $flatten = [];

        foreach ($collections->values() as $pageVariable) {
            /** @var PageVariable $pageVariable */

            foreach ($pageVariable->values() as $collectionItem) {
                $flatten[] = $collectionItem;
            }
        }

        foreach ($collections->values() as $pageVariable) {
            /** @var PageVariable $pageVariable */

            foreach ($pageVariable->values() as $collectionItem) {
                static::processElement($collectionItem, $flatten);
            }
        }
    }

    private static function log(string $text): void
    {
        print(' >> ' . $text . "\n");
    }

    /**
     * @param CollectionItem|FederationCardEntity $collectionItem
     * @param CollectionItem[] $flatten
     *
     * @throws Exception
     */
    private static function processElement(CollectionItem|FederationCardEntity $collectionItem, array $flatten)
    {
        if (!isset(self::MAPPING[$collectionItem::class])) {
            return;
        }

        $mapping = self::MAPPING[$collectionItem::class];

        foreach ($mapping as $parentField => $connection) {
            foreach ($flatten as $potentialChild) {
                /** @var FederationCardEntity $potentialChild */

                if (!$potentialChild instanceof $connection['class']) {
                    continue;
                }

                if ($connection['type'] === self::MANY_TO_ONE) {
                    $parentValue = $collectionItem->__get($parentField);
                    $childValue  = $potentialChild->__get($connection['field']);

                    if (in_array($childValue, $parentValue)) {
                        static::log('Wiring many-to-one [' . $connection['class'] . '] ' . $childValue . ' by ' . json_encode($parentValue) . ' (field=' . $parentField . ') to ' . get_class($collectionItem) . ' (' . $collectionItem->name . ')');
                        $collectionItem->applyRelation($potentialChild);
                        $potentialChild->applyRelation($collectionItem);
                    }

                } elseif ($connection['type'] === self::ONE_TO_ONE) {
                    $parentValue = $collectionItem->__get($parentField);
                    $childValue  = $potentialChild->__get($connection['field']);

                    if ($parentValue === $childValue) {
                        static::log('Wiring one-to-one [' . $connection['class'] . '] ' . $childValue . ' by ' . json_encode($parentValue) . ' (field=' . $parentField . ') to ' . get_class($collectionItem) . ' (' . $collectionItem->name . ')');
                        $collectionItem->applyRelation($potentialChild);
                        $potentialChild->applyRelation($collectionItem);

                        break;
                    } else {
                        dump($parentValue, $childValue, $connection['field']);
                    }

                } else {
                    throw new Exception('Invalid connection type "' . $connection['type'] . '"');
                }
            }
        }
    }
}
