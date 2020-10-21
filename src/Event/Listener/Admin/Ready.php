<?php

namespace Nails\UserSnap\Event\Listener\Admin;

use Nails\Admin\Events;
use Nails\Common\Exception\AssetException;
use Nails\Common\Exception\FactoryException;
use Nails\Common\Exception\NailsException;
use Nails\Config;

/**
 * Class Ready
 *
 * @package Nails\UserSnap\Event\Listener\System
 */
class Ready extends \Nails\UserSnap\Event\Listener\System\Ready
{
    /**
     * Ready constructor.
     */
    public function __construct()
    {
        $this
            ->setEvent(Events::ADMIN_READY)
            ->setCallback([$this, 'execute']);
    }

    // --------------------------------------------------------------------------

    /**
     * Handles the event
     *
     * @throws AssetException
     * @throws FactoryException
     * @throws NailsException
     */
    public function execute(): void
    {
        if (Config::get('USERSNAP_ADMIN_ENABLED', true)) {
            $this->inject();
        }
    }
}
