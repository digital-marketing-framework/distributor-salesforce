<?php

namespace DigitalMarketingFramework\Distributor\Salesforce\Route;

use DigitalMarketingFramework\Core\Model\Data\DataInterface;
use DigitalMarketingFramework\Core\SchemaDocument\FieldDefinition\FieldDefinition;
use DigitalMarketingFramework\Core\SchemaDocument\Schema\ContainerSchema;
use DigitalMarketingFramework\Core\SchemaDocument\Schema\SchemaInterface;
use DigitalMarketingFramework\Core\SchemaDocument\Schema\StringSchema;
use DigitalMarketingFramework\Core\DataProcessor\DataProcessor;
use DigitalMarketingFramework\Distributor\Request\Route\RequestOutboundRoute;

class SalesforceOutboundRoute extends RequestOutboundRoute
{
    public const KEY_OID = 'oid';

    public const DEFAULT_OID = '';

    public const KEY_DEBUG_EMAIL = 'debugEmail';

    public const DEFAULT_DEBUG_EMAIL = '';

    public static function getIntegrationName(): string
    {
        return 'salesforce';
    }

    public static function getIntegrationLabel(): ?string
    {
        return 'SalesForce';
    }

    public static function getOutboundRouteListLabel(): ?string
    {
        return null;
    }

    public static function getLabel(): ?string
    {
        return 'Web-To-Lead';
    }

    public function buildData(): DataInterface
    {
        $data = parent::buildData();

        $data['oid'] = $this->getConfig(static::KEY_OID);
        $data['encoding'] = 'UTF-8';
        $data['retURL'] = '#';

        $debugEmail = $this->getConfig(static::KEY_DEBUG_EMAIL);
        if ($debugEmail !== '') {
            $data['debug'] = '1';
            $data['debugEmail'] = $debugEmail;
        }

        return $data;
    }

    public static function getSchema(): SchemaInterface
    {
        /** @var ContainerSchema $schema */
        $schema = parent::getSchema();

        /** @var StringSchema $urlSchema */
        $urlSchema = $schema->getProperty(static::KEY_URL)->getSchema();
        $urlSchema->getSuggestedValues()->addValue('https://webto.salesforce.com/servlet/servlet.WebToLead?encoding=UTF-8');
        $urlSchema->getSuggestedValues()->addValue('https://test.salesforce.com/servlet/servlet.WebToLead?encoding=UTF-8');

        $oidSchema = new StringSchema(static::DEFAULT_OID);
        $oidSchema->setRequired();
        $schema->addProperty(static::KEY_OID, $oidSchema);

        $debugSchema = new StringSchema(static::DEFAULT_DEBUG_EMAIL);
        $schema->addProperty(static::KEY_DEBUG_EMAIL, $debugSchema);

        return $schema;
    }

    public static function getDefaultFields(): array
    {
        return [
            new FieldDefinition('recordType', type: FieldDefinition::TYPE_STRING, label: 'Record Type', multiValue: false, required: false),
            new FieldDefinition('lead_source', type: FieldDefinition::TYPE_STRING, label: 'Lead Source', multiValue: false, required: false),

            new FieldDefinition('first_name', type: FieldDefinition::TYPE_STRING, label: 'First Name', multiValue: false, required: false),
            new FieldDefinition('last_name', type: FieldDefinition::TYPE_STRING, label: 'Last Name', multiValue: false, required: false),
            new FieldDefinition('email', type: FieldDefinition::TYPE_STRING, label: 'Email', multiValue: false, required: true),
            new FieldDefinition('company', type: FieldDefinition::TYPE_STRING, label: 'Company', multiValue: false, required: false),
            new FieldDefinition('city', type: FieldDefinition::TYPE_STRING, label: 'City', multiValue: false, required: false),
            new FieldDefinition('country_code', type: FieldDefinition::TYPE_STRING, label: 'Country Code', multiValue: false, required: false),
            new FieldDefinition('state_code', type: FieldDefinition::TYPE_STRING, label: 'State Code', multiValue: false, required: false),
            new FieldDefinition('salutation', type: FieldDefinition::TYPE_STRING, label: 'Salutation', multiValue: false, required: false),
            new FieldDefinition('title', type: FieldDefinition::TYPE_STRING, label: 'Title', multiValue: false, required: false),
            new FieldDefinition('url', type: FieldDefinition::TYPE_STRING, label: 'URL', multiValue: false, required: false),
            new FieldDefinition('phone', type: FieldDefinition::TYPE_STRING, label: 'Phone', multiValue: false, required: false),
            new FieldDefinition('mobile', type: FieldDefinition::TYPE_STRING, label: 'Mobile', multiValue: false, required: false),
            new FieldDefinition('fax', type: FieldDefinition::TYPE_STRING, label: 'Fax', multiValue: false, required: false),
            new FieldDefinition('street', type: FieldDefinition::TYPE_STRING, label: 'Street', multiValue: false, required: false),
            new FieldDefinition('zip', type: FieldDefinition::TYPE_STRING, label: 'ZIP', multiValue: false, required: false),
            new FieldDefinition('country', type: FieldDefinition::TYPE_STRING, label: 'Country', multiValue: false, required: false),
            new FieldDefinition('state', type: FieldDefinition::TYPE_STRING, label: 'State', multiValue: false, required: false),
            new FieldDefinition('industry', type: FieldDefinition::TYPE_STRING, label: 'Industry', multiValue: false, required: false),

            new FieldDefinition('rating', type: FieldDefinition::TYPE_STRING, label: 'Rating', multiValue: false, required: false),
            new FieldDefinition('currency', type: FieldDefinition::TYPE_STRING, label: 'Currency', multiValue: false, required: false),
            new FieldDefinition('revenue', type: FieldDefinition::TYPE_STRING, label: 'Revenue', multiValue: false, required: false),
            new FieldDefinition('employees', type: FieldDefinition::TYPE_STRING, label: 'Employees', multiValue: false, required: false),
            new FieldDefinition('Campaign_ID', type: FieldDefinition::TYPE_STRING, label: 'Campaign ID', multiValue: false, required: false),
            new FieldDefinition('member_status', type: FieldDefinition::TYPE_STRING, label: 'Member Status', multiValue: false, required: false),

            new FieldDefinition('emailOptOut', type: FieldDefinition::TYPE_STRING, label: 'Email Opt Out', multiValue: false, required: false),
            new FieldDefinition('faxOptOut', type: FieldDefinition::TYPE_STRING, label: 'Fax Opt Out', multiValue: false, required: false),
            new FieldDefinition('doNotCall', type: FieldDefinition::TYPE_STRING, label: 'Do not call', multiValue: false, required: false),

            new FieldDefinition('description', type: FieldDefinition::TYPE_STRING, label: 'Description', multiValue: false, dedicated: FieldDefinition::DEDICATED_COLLECTOR_FIELD, required: false),
        ];
    }
}
