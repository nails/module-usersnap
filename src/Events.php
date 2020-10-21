<?php

/**
 * The class provides a summary of the events fired by this module
 *
 * @package     Nails
 * @subpackage  module-usersnap
 * @category    Events
 * @author      Nails Dev Team
 */

namespace Nails\UserSnap;

use Nails\UserSnap\Event\Listener;
use Nails\Common\Events\Base;
use Nails\Common\Events\Subscription;

/**
 * Class Events
 *
 * @package Nails\UserSnap
 */
class Events extends Base
{
    /**
     * Subscribe to events
     *
     * @return Subscription[]
     */
    public function autoload(): array
    {
        return [
            new Listener\Admin\Ready(),
            new Listener\System\Ready(),
        ];
    }
}
