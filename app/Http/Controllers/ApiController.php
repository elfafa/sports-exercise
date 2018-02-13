<?php

namespace App\Http\Controllers;

use App\Cruds\MatchCrud;
use App\Http\Transformers\MatchTransformer;
use App\Match;
use Symfony\Component\HttpFoundation\Response;
use App\Repositories\MatchRepository;

/**
 * Class ImportController
 *
 * @resource Get match statistics
 */
class ApiController extends Controller
{
    /**
     * Get match infos
     *
     * @param MatchCrud $matchCrud
     * @param MatchTransformer $matchTransformer
     * @param int $externalId
     * @return JsonResponse
     */
    public function get(
        MatchCrud $matchCrud,
        MatchTransformer $matchTransformer,
        $externalId
    ) {
        $match = $matchCrud->findWhere('external_id', $externalId);
        if (! $match) {
            return $this->respondWithJson([
                'error' => 'Unknow match'
            ], Response::HTTP_NOT_FOUND);
        }

        return $this->respondWithJson(
            $matchTransformer->transformModel($match)
        );
    }

    /**
     * Get top matches (best goals)
     *
     * @param MatchRepository $matchRepository
     * @param MatchTransformer $matchTransformer
     * @param int $minimumGoals
     * @return JsonResponse
     */
    public function getTop(
        MatchRepository $matchRepository,
        MatchTransformer $matchTransformer,
        $minimumGoals = MatchRepository::MINIMUM_GOALS
    ) {
        $matches = $matchRepository->getTop($minimumGoals);

        return $this->respondWithJson(
            $matchTransformer->transformCollection($matches)
        );
    }

    /**
     * Get team matches
     *
     * @param MatchRepository $matchRepository
     * @param MatchTransformer $matchTransformer
     * @param string $team
     * @param int $quantity
     * @return JsonResponse
     */
    public function getForTeam(
        MatchRepository $matchRepository,
        MatchTransformer $matchTransformer,
        $team,
        $quantity = MatchRepository::DEFAULT_QUANTITY
    ) {
        $matches = $matchRepository->getForTeam($team, $quantity);

        return $this->respondWithJson(
            $matchTransformer->transformCollection($matches)
        );
    }
}
