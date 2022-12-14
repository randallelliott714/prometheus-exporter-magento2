<?php

declare(strict_types=1);

namespace RunAsRoot\PrometheusExporter\Data;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Store\Model\ScopeInterface;
use RunAsRoot\PrometheusExporter\Model\SourceModel\Metrics as MetricsSource;

class NewRelicConfig
{
    private const CONFIG_PATH_NEWRELIC_METRICS_ENABLED = 'newrelic_configuration/metric/newrelic_metric_enabled';
    private const CONFIG_PATH_NEWRELIC_API_URL = 'newrelic_configuration/metric/api_url';
    private const CONFIG_PATH_NEWRELIC_API_KEY = 'newrelic_configuration/metric/api_key';
    private const CONFIG_PATH_NEWRELIC_METRIC_STATUS = 'newrelic_configuration/metric/metric_status';
    private const CONFIG_PATH_NEWRELIC_INSTANCE_NAME = 'newrelic_configuration/metric/instance_name';
    private const CONFIG_PATH_NEWRELIC_DEBUG_ENABLED = 'newrelic_configuration/metric/debug_enabled';
    private const CONFIG_PATH_NEWRELIC_CRON_ENABLED = 'newrelic_configuration/cron/cron_enabled';

    private ScopeConfigInterface $config;
    private MetricsSource $metricsSource;
    private EncryptorInterface $encryptor;

    public function __construct(
        ScopeConfigInterface $config,
        MetricsSource $metricsSource,
        EncryptorInterface $encryptor
    ) {
        $this->config = $config;
        $this->metricsSource = $metricsSource;
        $this->encryptor = $encryptor;
    }

    public function isEnabled(?string $scopeCode = null): bool
    {
        return (bool)$this->config->isSetFlag(self::CONFIG_PATH_NEWRELIC_METRICS_ENABLED, ScopeInterface::SCOPE_STORE, $scopeCode);
    }

    public function getApiUrl(?string $scopeCode = null): string
    {
        return $this->config->getValue(self::CONFIG_PATH_NEWRELIC_API_URL, ScopeInterface::SCOPE_STORE, $scopeCode);
    }

    public function getApiKey(?string $scopeCode = null): string
    {
        $encryptedValue = $this->config->getValue(self::CONFIG_PATH_NEWRELIC_API_KEY, ScopeInterface::SCOPE_STORE, $scopeCode);
        return $this->encryptor->decrypt($encryptedValue);
    }

    public function getMetricsStatus(?string $scopeCode = null): array
    {
        $metrics = $this->config->getValue(self::CONFIG_PATH_NEWRELIC_METRIC_STATUS, ScopeInterface::SCOPE_STORE, $scopeCode);

        if ($metrics === null) {
            return $this->getDefaultMetrics();
        }

        return explode(',', $metrics);
    }

    public function getInstanceName(?string $scopeCode = null): string
    {
        return $this->config->getValue(self::CONFIG_PATH_NEWRELIC_INSTANCE_NAME, ScopeInterface::SCOPE_STORE, $scopeCode);
    }

    public function isDebugEnabled(?string $scopeCode = null): bool
    {
        return (bool)$this->config->isSetFlag(self::CONFIG_PATH_NEWRELIC_DEBUG_ENABLED, ScopeInterface::SCOPE_STORE, $scopeCode);
    }

    public function isCronEnabled(?string $scopeCode = null): bool
    {
        return (bool)$this->config->isSetFlag(self::CONFIG_PATH_NEWRELIC_CRON_ENABLED, ScopeInterface::SCOPE_STORE, $scopeCode);
    }

    public function getDefaultMetrics(): array
    {
        return array_column($this->metricsSource->toOptionArray(), 'value');
    }
}
