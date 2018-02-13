<?php

namespace App\Cruds;

use App\Match;
use App\Repositories\StatisticRepo;
use Nathanmac\Utilities\Parser\Parser;
use GuzzleHttp\Client;

/**
 * Class StatisticCrud
 */
class StatisticCrud extends AbstractCrud
{
    private $match;
    private $parser;
    private $client;
    private $specificCrud;

    /**
     * @param StatisticRepo $repository
     * @param Parser $parser
     * @param Client $client
     */
    public function __construct(
        StatisticRepo $repository,
        Parser $parser,
        Client $client
    ) {
        $this->repository = $repository;
        $this->parser     = $parser;
        $this->client     = $client;
    }

    public function setMatch(Match $match)
    {
        $this->match    = $match;
        $this->instance = $match->statistic;
        if ('football' === $match->sport) {
            $this->specificCrud = new StatisticSoccerCrud;
        } else {
            // exception
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function create()
    {
        $xml   = $this->getFileContent();
        $datas = $this->specificCrud->getAnalyzedXml($this->parser->xml($xml));
        $this->setData(array_merge(
            [ 'match_id' => $this->match->id ],
            $datas
        ));
        parent::create();

        return $this->instance;
    }

    /**
     * {@inheritdoc}
     */
    public function update()
    {
        $xml   = $this->getFileContent();
        $datas = $this->specificCrud->getAnalyzedXml($this->parser->xml($xml));
        $this->setData(array_merge(
            [ 'match_id' => $this->match->id ],
            $datas
        ));
        parent::update();

        return $this->instance;
    }

    protected function getFileContent()
    {
        $response = $this->client->get($this->match->feed_file);
        $body     = $response->getBody();
        $body->seek(0);
        $size     = $body->getSize();

        return $body->read($size);
    }
}
