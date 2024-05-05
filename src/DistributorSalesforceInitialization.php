<?php

namespace DigitalMarketingFramework\Distributor\Salesforce;

use DigitalMarketingFramework\Core\Initialization;
use DigitalMarketingFramework\Core\Registry\RegistryDomain;
use DigitalMarketingFramework\Distributor\Core\Route\OutboundRouteInterface;
use DigitalMarketingFramework\Distributor\Salesforce\Route\SalesforceOutboundRoute;

class DistributorSalesforceInitialization extends Initialization
{
    protected const PLUGINS = [
        RegistryDomain::DISTRIBUTOR => [
            OutboundRouteInterface::class => [
                SalesforceOutboundRoute::class,
            ],
        ],
    ];

    protected const SCHEMA_MIGRATIONS = [];

    public function __construct(string $packageAlias = '')
    {
        parent::__construct('distributor-salesforce', '1.0.0', $packageAlias);
    }
}
