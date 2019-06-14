<?php

declare(strict_types=1);

/**
 * @copyright see PROJECT_LICENSE.txt
 * @see PROJECT_LICENSE.txt
 */

namespace RunAsRoot\PrometheusExporter\Result;

use Magento\Framework\App\Response\HttpInterface as HttpResponseInterface;
use Magento\Framework\Controller\Result\Raw;
use RunAsRoot\PrometheusExporter\Repository\MetricRepository;

class PrometheusResult extends Raw
{
    /**
     * @var MetricRepository
     */
    private $metricRepository;

    public function __construct(MetricRepository $metricRepository)
    {
        $this->metricRepository = $metricRepository;
    }

    protected function render(HttpResponseInterface $response)
    {
        parent::render($response);
        #$formatedMetrics = $this->collectMetrics();
        #$this->setContents($formatedMetrics);
        $this->setContents(<<<HEREDOC
# TYPE magento2_orders_amount_total gauge
# HELP magento2_orders_amount_total Super Duper Metric
magento2_orders_amount_total 39.14
HEREDOC
        );

        $response->setBody($this->contents);
        $response->setHeader('Content-Type', 'text/plain; charset=UTF-8', true);
        $this->setHeader('Content-Type', 'text/plain; charset=UTF-8', true);

        return $this;
    }

    protected function collectMetrics(): string
    {

        #$metrics = $this->metricRepository->getList();

        $formatedMetrics = '';
        foreach ($metrics as $metric) {

        }

        return $formatedMetrics;
    }
}
