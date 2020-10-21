<?php

namespace Nails\UserSnap\Event\Listener\System;

use Nails\Common\Events;
use Nails\Common\Events\Subscription;
use Nails\Common\Exception\AssetException;
use Nails\Common\Exception\FactoryException;
use Nails\Common\Exception\NailsException;
use Nails\Common\Service\Asset;
use Nails\Config;
use Nails\Environment;
use Nails\Factory;

/**
 * Class Ready
 *
 * @package Nails\UserSnap\Event\Listener\System
 */
class Ready extends Subscription
{
    /**
     * Ready constructor.
     */
    public function __construct()
    {
        $this
            ->setEvent(Events::SYSTEM_READY)
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
        if (Config::get('USERSNAP_FRONTEND_ENABLED', true)) {
            $this->inject();
        }
    }

    // --------------------------------------------------------------------------

    /**
     * Injects the UserSnap code
     *
     * @throws AssetException
     * @throws FactoryException
     * @throws NailsException
     */
    protected function inject(): void
    {
        $sKey         = Config::get('USERSNAP_KEY');
        $aEnvironment = (array) Config::get('USERSNAP_ENV');

        if ($sKey && (empty($aEnvironment) || Environment::is($aEnvironment))) {

            /** @var Asset $oAsset */
            $oAsset = Factory::service('Asset');
            $oAsset->inline(sprintf(
                '(function() {
                var s = document.createElement("script");
                s.type = "text/javascript";
                s.async = true;
                s.src = \'//api.usersnap.com/load/%s.js\';
                var x = document.getElementsByTagName(\'script\')[0];
                x.parentNode.insertBefore(s, x);
                })();',
                $sKey
            ), 'JS');
        }
    }
}
