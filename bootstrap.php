<?php declare(strict_types=1);

use App\Mechanism\Service\CollectionConnector;
use TightenCo\Jigsaw\Jigsaw;

/** @var $container \Illuminate\Container\Container */
/** @var $events \TightenCo\Jigsaw\Events\EventBus */
/** @var callable $afterCollections */

/**
 * You can run custom code at different stages of the build process by
 * listening to the 'beforeBuild', 'afterCollections', and 'afterBuild' events.
 *
 * For example:
 *
 * $events->beforeBuild(function (Jigsaw $jigsaw) {
 *     // Your code here
 * });
 */


$events->afterCollections(function (Jigsaw $jigsaw) {
    /**
     * Connect all entities together, e.g. Organization -> Location
     *                                     Organization -> Activity
     */
    CollectionConnector::process($jigsaw->getCollections());
});
