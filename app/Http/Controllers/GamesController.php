<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\User;
use App\Game;
use App\GameTurns;
use App\GameMessage;
use App\Player;
use Carbon\Carbon;

class GamesController extends Controller {
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return view
     */
    public function index() {
        // Remove old and empty games from the list.
        $oldGames = Game::where('created_at', '<=', Carbon::now()->subHours(3))->get();
        if ($oldGames->count() > 0) {
            foreach ($oldGames as $key=>$game) {
                if ($game->players()->count() == 0) {
                    $game->delete();
                } 
            }
        }

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
        $game = Game::where('url', '=', $url);
        if ($game->count() > 0) {
            $game = $game->first();
            return view('games.game', compact('game'));
        }
        return view('games.404');
    }

    /**
     * Handle request for a list of Players including all their data.
     *
     * @return Response
     */
    public function getPlayerList(Request $request, $url) {
        //if (!$request->ajax()) { return redirect('/game/'.$url); }
        $game = Game::where('url', '=', $url);
        if ($game->count() > 0) {
            $game = $game->first();
            $players = Player::notPartOf( $request->input('uIDs') != null ? $request->input('uIDs') : [], $game->id);
            if ($players->count() > 0) {
                $players = $players->get()->sortBy('created_at')->values()->all();
                foreach ($players as $key => $player) {
                    $player->user_data = User::where('id', '=', $player->user_id)->first();
                }
                $time = Carbon::now()->timestamp;
                return compact('players', 'time');
            }
            return [];
        }
        return;
    }

    /**
     * Handle request for a list of Players only including a specific record from their data.
     *
     * @return Response
     */
    public function getPlayerData(Request $request, $url) {
        //if (!$request->ajax()) { return redirect('/game/'.$url); }
        $game = Game::where('url', '=', $url);
        if ($game->count() > 0) {
            $game = $game->first();
            $players = Player::where('game_id', '=', $game->id);
            if ($players->count() > 0) {
                $players = $players->get()->keyBy('user_id')->all();
                $keyData = $request->input('keyData') != null ? $request->input('keyData') : [];
                //dd( [$players, $keyData] );
                foreach ($keyData as $key => $value) {
                    if ($players[$key] != null && $players[$key][$request->input('key')] == $value) {
                        unset($keyData[$key]);
                    }
                    else {
                        $keyData[$key] = $players[$key][$request->input('key')];
                    }
                }
                $players = $keyData;
                return compact('players');
            }
            return [];
        }
        return;
    }

    /**
     * Handle request for a list of Players only including a specific record from their data.
     *
     * @return Response
     */
    public function getTurnData(Request $request, $url) {
        //if (!$request->ajax()) { return redirect('/game/'.$url); }
        $game = Game::where('url', '=', $url);
        if ($game->count() > 0) {
            $game = $game->first();
            $turns = GameTurns::where('id', '>', $request->input('tID') != null ? $request->input('tID') : 0);
            if ($turns->count() > 0) {
                $turns = $turns->get();
                foreach ($turns as $key => $turn) {
                    $turn->data = json_decode($turn->data);
                }
                $status = 0;
                return compact('turns', 'status');
            }
            return ['status' => 1];
        }
        return ['status' => 1];
    }

    /**
     * Handle Join Data.
     *
     * @return Response - True if they are a player, false if a spectator.
     */
    public function postJoin(Request $request, $url) {
        $players = Player::where('user_id', '=', $request->user()->id);
        if ($players->count() > 0) {
            $player = $players->first();
            $player->character = $request->input('character');
            $player->palette = $request->input('palette');
            $player->save();
            return ['status' => 2];
        }
        $game = Game::where('url', '=', $url);
        if ($game->count() > 0) {
            $game = $game->first();
            if ($game->players()->count() < 4) {
                $player = Player::create([
                    'game_id' => $game->id, 
                    'user_id' => $request->user()->id, 
                    'character' => $request->input('character'), 
                    'palette' => $request->input('palette'),
                ]);
                return ['status' => 1];
            }
            return ['status' => 3];
        }
    }

    /**
     * Helper to get players within a certain stage.
     *
     * @return Response
     */
    protected function getPlayersStage(Request $request, $url, $stage) {
        $game = Game::where('url', '=', $url);
        if ($game->count() > 0) {
            $game = $game->first();
            $players = Player::inGameState($request->user()->id, $game->id, $stage)->sortBy('created_at')->get();
            foreach ($players as $key => $player) {
                $player->user_data = User::where('id', '=', $player->user_id)->first();
            }
            return $players;
        }
        return view('games.404');
    }

    /**
     * Helper to set players to a certain stage.
     *
     * @return Response
     */
    protected function setPlayersStage(Request $request, $url, $stage) {
        $game = Game::where('url', '=', $url);
        if ($game->count() > 0) {
            $game = $game->first();
            $player = Player::where('user_id', '=', $request->user()->id)->where('game_id', '=', $game->id);
            if ($player->count() > 0) {
                $player = $player->first();
                $player->state = $stage;
                $player->save();
                return ['status' => 1];
            }
            return ['status' => 2];
        }
        return ['status' => 3];
    }

    /**
     * Handle Player Ready Data.
     *
     * @return Response
     */
    public function getReady(Request $request, $url) {
        return $this->getPlayersStage($request, $url, 1);
    }

    /**
     * Handle Player Ready Post.
     *
     * @return Response
     */
    public function postReady(Request $request, $url) {
        $returnData = $this->setPlayersStage($request, $url, 1);
        if ($returnData['status'] != 3) {
            $game = Game::where('url', '=', $url)->first();
            if ( Player::where('game_id', '=', $game->id)->where('state', '!=', '1')->count() == 0 ) {
                $game->state = 1;
                $game->save();
            }
        }
        return $returnData;
    }

    /**
     * Handle Player Loaded Data.
     *
     * @return Response
     */
    public function getLoaded(Request $request, $url) {
        return $this->getPlayersStage($request, $url, 2);
    }

    /**
     * Handle Player Loaded Post.
     *
     * @return Response
     */
    public function postLoaded(Request $request, $url) {
        $returnData = $this->setPlayersStage($request, $url, 2);
        if ($returnData['status'] != 3) {
            $game = Game::where('url', '=', $url)->first();
            $query = Player::where('game_id', '=', $game->id);
            $players = $query->get()->sortBy('created_at')->values()->first();
            if ($query->where('state', '!=', '2')->count() == 0) {
                $players->state = 3;
                $players->save();
                $game->state = 2;
                $game->save();
            }
        }
        return $returnData;
    }

    /**
     * Handle Join Data.
     *
     * @return Response
     */
    public function postQuit(Request $request, $url) {
        $game = Game::where('url', '=', $url);
        if ($game->count() > 0) {
            $game = $game->first();
        }
    }

    /**
     * Handle Roll Data.
     *
     * @return Response
     */
    public function postRoll(Request $request, $url) {
        $status = 1;
        $game = Game::where('url', '=', $url);
        if ($game->count() > 0) {
            $game = $game->first();
            $currPlayer = Player::where('user_id', '=', $request->user()->id)->where('game_id', '=', $game->id);
            if ($currPlayer->count() == 0 || $currPlayer->first()->state != 3) 
                return compact('status');

            $turns = GameTurns::create(['game_id' => $game->id, 'user_id' => $request->user()->id, 'data' => json_encode(['type' => 1, 'roll' => rand(1, 12)]) ]);

            if (!isset($turns->data->joinLast)) {
                $players = Player::where('game_id', '=', $game->id)->get()->sortBy('created_at');
                foreach ($players as $key => $player) {
                    if ($player->state == 3) {
                        $currPly = $key;
                        $nextPly = ($key + 1) % $players->count();
                    }
                }
                $players[$currPly]->state = 2;
                $players[$currPly]->save();

                $players[$nextPly]->state = 3;
                $players[$nextPly]->save();
            }
            $status = 0;
            $turns->data = json_decode($turns->data);
            $turns = [$turns];
        }
        return compact('status');
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
