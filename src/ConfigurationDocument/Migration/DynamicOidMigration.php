<?php

namespace DigitalMarketingFramework\Distributor\Salesforce\ConfigurationDocument\Migration;

use DigitalMarketingFramework\Core\ConfigurationDocument\Migration\ConfigurationDocumentMigration;
use DigitalMarketingFramework\Core\ConfigurationDocument\Migration\MigrationContext;
use DigitalMarketingFramework\Core\DataProcessor\DataProcessor;
use DigitalMarketingFramework\Core\Model\Configuration\ConfigurationInterface;
use DigitalMarketingFramework\Core\SchemaDocument\Schema\SwitchSchema;
use DigitalMarketingFramework\Core\Utility\AbstractListUtility;
use DigitalMarketingFramework\Distributor\Core\Model\Configuration\DistributorConfigurationInterface;
use DigitalMarketingFramework\Distributor\Salesforce\Route\SalesforceOutboundRoute;

class DynamicOidMigration extends ConfigurationDocumentMigration
{
    public function getSourceVersion(): string
    {
        return '1.0.0';
    }

    public function getTargetVersion(): string
    {
        return '1.0.1';
    }

    public function migrate(array $delta, MigrationContext $context): array
    {
        $integrationKeyword = 'salesforce';
        $routeKeyword = 'salesforce';
        $completeConfiguration = $context->getEffectiveConfiguration($delta);

        $intKey = ConfigurationInterface::KEY_INTEGRATIONS;
        $routesKey = DistributorConfigurationInterface::KEY_OUTBOUND_ROUTES;
        $valueKey = AbstractListUtility::KEY_VALUE;
        $typeKey = SwitchSchema::KEY_TYPE;
        $configKey = SwitchSchema::KEY_CONFIG;
        $oidKey = SalesforceOutboundRoute::KEY_OID;

        foreach ($completeConfiguration[$intKey][$integrationKeyword][$routesKey] ?? [] as $uuid => $route) {
            $currentRouteKeyword = $route[$valueKey][$typeKey] ?? '';
            if ($currentRouteKeyword !== $routeKeyword) {
                continue;
            }

            if (!isset($delta[$intKey][$integrationKeyword][$routesKey][$uuid][$valueKey][$configKey][$routeKeyword][$oidKey])) {
                continue;
            }

            $oid = $delta[$intKey][$integrationKeyword][$routesKey][$uuid][$valueKey][$configKey][$routeKeyword][$oidKey];
            if (is_string($oid)) {
                $delta[$intKey][$integrationKeyword][$routesKey][$uuid][$valueKey][$configKey][$routeKeyword][$oidKey] = DataProcessor::valueSchemaDefaultValueConstant($oid);
            }
        }

        return $delta;
    }
}
