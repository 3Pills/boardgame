<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\User;
use App\Game;
use App\GameMessage;
use Carbon\Carbon;

class GamesController extends Controller {
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return view
     */
    public function index() {
    	return view('games.index');
    }

    /**
     * Store game into database.
     *
     * @param  array $data
     * @return Response
     */
    public function create(Request $request) {
        $data = $request->all();
        do {
            $data['url'] = str_random(5);
        } while (Game::where('url', '=', $data['url'])->get()->count());
        $game = Game::create($data);
        return redirect('/game/'.$game->url);
    }

    /**
     * View Game information.
     *
     * @return Response
     */
    public function show($url) {
        $game = Game::where('url', '=', $url)->first();
        if ($game === null) {
            return view('games.404');
        }
        return view('games.game', compact('game'));
    }

    /**
     * Handle Join Data.
     *
     * @return Response
     */
    public function postJoin(Request $request, $url) {
        $game = Game::where('url', '=', $url)->first();

        //Get the stage of joining the user is in.
        $stage = $request->input('stage');

        //Final response is true if they are a player, false if a spectator.
        return false;
    }

    /**
     * Handle Join Data.
     *
     * @return Response
     */
    public function postQuit(Request $request, $url) {
        $game = Game::where('url', '=', $url)->first();
    }

    /**
     * Handle Roll Data.
     *
     * @return Response
     */
    public function postRoll(Request $request, $url) {
        return rand(1, 11);
    }

    /**
     * Handle Chat Data.
     *
     * @return Response
     */
    public function postChat(Request $request, $url) {
        GameMessage::create(['user_id' => $request->user()->id, 'game_id' => Game::where('url', '=', $url)->first()->id, 'msg' => $request->input('msg')]);
        return $request->user()->name;
    }

    /**
     * Handle Chat Request.
     *
     * @return Response
     */
    public function getChat(Request $request, $url) {
        //We can find the game the chat request was for via the url variable.
        $game = Game::where('url', '=', $url)->first();
        if ($game) {
            //Generate a list of all game chat instances after the latest chat the client has.
            $msgList = GameMessage::where( 'game_id', '=', $game->id )
                                  ->where( 'created_at', '>', Carbon::parse(($request->input('latest_chat'))) )->get();
            //Checking if we actually got any results
            if ($msgList->count()) {
                $data = $msgList->toArray(); //Store database data as array.

                //Create user_data entry for storing extra user info, as messages only contain a user's id.
                $data['user_data'] = []; 

                //Store the data of each user featured in the found messages.
                foreach ($msgList as $num => $msgData) {
                    if (!array_key_exists($msgData->user_id, $data['user_data'])) {
                        $data['user_data'][$msgData->user_id] = User::where('id', '=', $msgData->user_id)->first()->toArray();
                    }
                }
                return $data;
            }
        }
    }
}
