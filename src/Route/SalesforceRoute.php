<?php

namespace DigitalMarketingFramework\Distributor\Salesforce\Route;

use DigitalMarketingFramework\Core\ConfigurationDocument\SchemaDocument\Schema\ContainerSchema;
use DigitalMarketingFramework\Core\ConfigurationDocument\SchemaDocument\Schema\SchemaInterface;
use DigitalMarketingFramework\Core\ConfigurationDocument\SchemaDocument\Schema\StringSchema;
use DigitalMarketingFramework\Core\DataProcessor\DataProcessor;
use DigitalMarketingFramework\Distributor\Request\Route\RequestRoute;

class SalesforceRoute extends RequestRoute
{
    public static function getSchema(): SchemaInterface
    {
        /** @var ContainerSchema $schema */
        $schema = parent::getSchema();

        /** @var StringSchema $urlSchema */
        $urlSchema = $schema->getProperty(static::KEY_URL)->getSchema();
        $urlSchema->getSuggestedValues()->addValue('https://webto.salesforce.com/servlet/servlet.WebToLead?encoding=UTF-8');
        $urlSchema->getSuggestedValues()->addValue('https://test.salesforce.com/servlet/servlet.WebToLead?encoding=UTF-8');

        return $schema;
    }

    protected static function getDefaultFields(): array
    {
        return [
            'oid' => DataProcessor::valueSchemaDefaultValueConstant(''),
            'debug' => DataProcessor::valueSchemaDefaultValueNull(),
            'debugEmail' => DataProcessor::valueSchemaDefaultValueNull(),

            'recordType' => DataProcessor::valueSchemaDefaultValueField('record_type'),
            'lead_source' => DataProcessor::valueSchemaDefaultValueField('lead_source'),

            'first_name' => DataProcessor::valueSchemaDefaultValueField('first_name'),
            'last_name' => DataProcessor::valueSchemaDefaultValueField('last_name'),
            'email' => DataProcessor::valueSchemaDefaultValueField('email'),
            'company' => DataProcessor::valueSchemaDefaultValueField('company'),
            'city' => DataProcessor::valueSchemaDefaultValueField('city'),
            'country_code' => DataProcessor::valueSchemaDefaultValueField('country_code'),
            'state_code' => DataProcessor::valueSchemaDefaultValueField('state_code'),
            'saluation' => DataProcessor::valueSchemaDefaultValueField('salutation'),
            'title' => DataProcessor::valueSchemaDefaultValueField('title'),
            'url' => DataProcessor::valueSchemaDefaultValueField('url'),
            'phone' => DataProcessor::valueSchemaDefaultValueField('phone'),
            'mobile' => DataProcessor::valueSchemaDefaultValueField('mobile'),
            'fax' => DataProcessor::valueSchemaDefaultValueField('fax'),
            'street' => DataProcessor::valueSchemaDefaultValueField('street'),
            'zip' => DataProcessor::valueSchemaDefaultValueField('zip'),
            'country' => DataProcessor::valueSchemaDefaultValueField('country'),
            'state' => DataProcessor::valueSchemaDefaultValueField('state'),
            'industry' => DataProcessor::valueSchemaDefaultValueField('industry'),

            'rating' => DataProcessor::valueSchemaDefaultValueField('rating'),
            'currency' => DataProcessor::valueSchemaDefaultValueField('currency'),
            'revenue' => DataProcessor::valueSchemaDefaultValueField('revenue'),
            'employees' => DataProcessor::valueSchemaDefaultValueField('employees'),
            'Campaign_ID' => DataProcessor::valueSchemaDefaultValueField('campaign_id'),
            'member_status' => DataProcessor::valueSchemaDefaultValueField('member_status'),

            'emailOptOut' => DataProcessor::valueSchemaDefaultValueField('email_opt_out'),
            'faxOptOut' => DataProcessor::valueSchemaDefaultValueField('fax_opt_out'),
            'doNotCall' => DataProcessor::valueSchemaDefaultValueField('do_not_call'),

            'description' => DataProcessor::valueSchemaDefaultValueFieldCollector(),
        ];
    }
}
