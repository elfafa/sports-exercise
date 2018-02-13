<?php

namespace App\Cruds;

/**
 * Interface StatisticSpecificInterface
 */
interface StatisticSpecificInterface
{
    /**
     * Extract datas from given XML array
     *
     * @param array $xml
     * @return array
     */
    public function getAnalyzedXml(array $xml);
}
