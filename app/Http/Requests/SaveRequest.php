<?php

namespace App\Http\Requests;

/**
 * Class SaveRequest
 */
class SaveRequest extends AbstractRequest
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            'competition' => 'required',
            'match_id'    => 'required|integer',
            'season'      => 'required|integer',
            'sport'       => 'required',
            'teams.home'  => 'required',
            'teams.away'  => 'required',
            'created_at'  => 'required|date',
            'updated_at'  => 'required|date',
            'feed_file'   => 'required|url',
        ];
    }
}
