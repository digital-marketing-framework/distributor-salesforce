<?php

namespace DigitalMarketingFramework\Distributor\Salesforce\Tests\Unit\ConfigurationDocument\Migration;

use DigitalMarketingFramework\Core\ConfigurationDocument\Migration\MigrationContext;
use DigitalMarketingFramework\Core\DataProcessor\DataProcessor;
use DigitalMarketingFramework\Distributor\Salesforce\ConfigurationDocument\Migration\DynamicOidMigration;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(DynamicOidMigration::class)]
class DynamicOidMigrationTest extends TestCase
{
    protected DynamicOidMigration $subject;

    protected function setUp(): void
    {
        $this->subject = new DynamicOidMigration('distributor-salesforce');
    }

    /**
     * @param array<string,mixed> $routeConfig
     *
     * @return array<string,mixed>
     */
    protected function buildRoute(string $uuid, ?string $type, array $routeConfig = [], string $routeKeyword = 'salesforce'): array
    {
        $value = [];
        if ($type !== null) {
            $value['type'] = $type;
        }

        if ($routeConfig !== []) {
            $value['config'][$routeKeyword] = $routeConfig;
        }

        return [
            'integrations' => [
                'salesforce' => [
                    'outboundRoutes' => [
                        $uuid => [
                            'uuid' => $uuid,
                            'weight' => 10,
                            'value' => $value,
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * @param array<string,mixed> $configuration
     */
    protected function getOid(array $configuration, string $uuid, string $routeKeyword = 'salesforce'): mixed
    {
        return $configuration['integrations']['salesforce']['outboundRoutes'][$uuid]['value']['config'][$routeKeyword]['oid'] ?? null;
    }

    #[Test]
    public function stringOidBecomesConstantValue(): void
    {
        $delta = $this->buildRoute('route1', 'salesforce', ['oid' => '00D000000000001']);

        $result = $this->subject->migrate($delta, new MigrationContext([], []));

        $this->assertSame(DataProcessor::valueSchemaDefaultValueConstant('00D000000000001'), $this->getOid($result, 'route1'));
    }

    #[Test]
    public function stringOidIsMigratedWhenRouteTypeIsInherited(): void
    {
        $parent = $this->buildRoute('route1', 'salesforce');
        $delta = $this->buildRoute('route1', null, ['oid' => '00D000000000002']);

        $result = $this->subject->migrate($delta, new MigrationContext([$parent], []));

        $this->assertSame(DataProcessor::valueSchemaDefaultValueConstant('00D000000000002'), $this->getOid($result, 'route1'));
    }

    #[Test]
    public function emptyStringOidBecomesEmptyConstantValue(): void
    {
        $delta = $this->buildRoute('route1', 'salesforce', ['oid' => '']);

        $result = $this->subject->migrate($delta, new MigrationContext([], []));

        $this->assertSame(DataProcessor::valueSchemaDefaultValueConstant(''), $this->getOid($result, 'route1'));
    }

    #[Test]
    public function oidThatIsAlreadyAComplexValueIsLeftAlone(): void
    {
        $complexValue = DataProcessor::valueSchemaDefaultValueConstant('00D000000000003');
        $delta = $this->buildRoute('route1', 'salesforce', ['oid' => $complexValue]);

        $result = $this->subject->migrate($delta, new MigrationContext([], []));

        $this->assertSame($delta, $result);
    }

    #[Test]
    public function inheritedOidIsNotCopiedIntoTheDelta(): void
    {
        $parent = $this->buildRoute('route1', 'salesforce', ['oid' => DataProcessor::valueSchemaDefaultValueConstant('00D000000000004')]);
        $delta = $this->buildRoute('route1', null, ['debugEmail' => 'debug@example.com']);

        $result = $this->subject->migrate($delta, new MigrationContext([$parent], []));

        $this->assertSame($delta, $result);
    }

    #[Test]
    public function routesOfOtherTypesAreLeftAlone(): void
    {
        $delta = $this->buildRoute('route1', 'salesforceCase', ['oid' => 'not-a-lead-route'], 'salesforceCase');

        $result = $this->subject->migrate($delta, new MigrationContext([], []));

        $this->assertSame($delta, $result);
    }
}
