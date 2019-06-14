# Magento 2 Prometheus Exporter

![Branch stable](https://img.shields.io/badge/stable%20branch-master-blue.svg)
![Branch Develop](https://img.shields.io/badge/dev%20branch-develop-blue.svg)
![](https://github.styleci.io/repos/191891355/shield)

This Magento 2 Module exposes a new route under /metrics with Magento 2 specific metrics in the format of [prometheus](https://prometheus.io).
The different metrics are grouped into modules and can be enabled/disabled via the Magento Backend.

## Installation

Install the Module via composer by running: 

```
composer require run-as-root/magento2-prometheus-exporter
php bin/magento setup:upgrade
```

## Prometheus Configuration

After installing the Magento Module, your Prometheus needs to get pointed to your Magento Metrics endpoint. To do so, add the following lines to your
prometheus.yml under scrape_configs: 

```yaml
- job_name: 'Magento 2 Exporter'
  scrape_interval: 5m
  scrape_timeout: 60s
  metrics_path: /metrics
  static_configs:
  - targets: 
    - your-magento-url
```

## Module functionality

The module registers a cron job that runs every minute. The cronjob is
responsible for aggregating the metric data. The aggregated data is
stored in the table `run_as_root_prometheus_metrics`. The added
controller collects the data stored in the table and renders the correct
response for prometheus.

## Metrics

The following metrics will be collected:

| Metric | Labels | TYPE | Help | 
| --- | --- | --- | --- |
| magento_orders_count_total | status | gauge | All Magento Orders |
| magento_orders_amount_total | status | gauge | Total amount of all Magento Orders |
| magento_order_items_count_total | status | gauge | Total count of orderitems |
| magento2_cms_block_count_total | | gauge | Total count of available cms blocks |
| magento2_cms_page_count_total | | gauge | Total count of available cms pages |

## Contribution

If you have something to contribute, weither it's a feature, a feature request, an issue or something else, feel free to. There are no contribution guidelines.
