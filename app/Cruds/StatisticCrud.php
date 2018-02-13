<?php

namespace App\Cruds;

use App\Match;
use App\Repositories\StatisticRepository;
use Nathanmac\Utilities\Parser\Parser;
use GuzzleHttp\Client;

/**
 * Class StatisticCrud
 */
class StatisticCrud extends AbstractCrud
{
    /**
     * @var Match
     */
    private $match;

    /**
     * @var Parser
     */
    private $parser;

    /**
     * @var Client
     */
    private $client;

    /**
     * @var StatisticSpecificInterface
     */
    private $specificCrud;

    /**
     * @param StatisticRepository $repository
     * @param Parser $parser
     * @param Client $client
     */
    public function __construct(
        StatisticRepository $repository,
        Parser $parser,
        Client $client
    ) {
        $this->repository = $repository;
        $this->parser     = $parser;
        $this->client     = $client;
    }

    /**
     * Set match instance
     *
     * @param Match $match
     * @return $this
     */
    public function setMatch(Match $match)
    {
        $this->match    = $match;
        $this->instance = $match->statistic;
        if ('football' === strtolower($match->sport)) {
            $this->specificCrud = new StatisticFootballCrud;
        } else {
            throw new \Exception('Invalid sport');
        }

        return $this;
    }

    /**
     * {@inheridocs}
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
        $this->updateMatch($datas);

        return $this->instance;
    }

    /**
     * {@inheridocs}
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
        $this->updateMatch($datas);

        return $this->instance;
    }

    /**
     * Update related match instance
     */
    protected function updateMatch(array $datas)
    {
        $this->match->team_home_id = $datas['team_home_id'];
        $this->match->team_away_id = $datas['team_away_id'];
        $this->match->save();
    }

    /**
     * Get match feed file content
     *
     * @return string
     */
    protected function getFileContent()
    {
        $response = $this->client->get($this->match->feed_file);
        $body     = $response->getBody();
        $body->seek(0);
        $size     = $body->getSize();

        return $body->read($size);
    }
}
