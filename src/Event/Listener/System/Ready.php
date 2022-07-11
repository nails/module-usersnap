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
        /** @var string[] [description] */
        $aEnvironment = (array) Config::get('USERSNAP_ENV');
        $sKey         = Config::get('USERSNAP_KEY');

        if ($sKey && (empty($aEnvironment) || Environment::is($aEnvironment))) {

            /** @var Asset $oAsset */
            $oAsset = Factory::service('Asset');
            $oAsset->inline(sprintf(
                'window.onUsersnapCXLoad = function(api) {
                    api.init();
                }
                var script = document.createElement(\'script\');
                script.defer = 1;
                script.src = \'https://widget.usersnap.com/global/load/%s?onload=onUsersnapCXLoad\';
                document.getElementsByTagName(\'head\')[0].appendChild(script);',
                $sKey
            ), 'JS');
        }
    }
}
