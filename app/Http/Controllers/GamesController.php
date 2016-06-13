<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\User;
use App\Game;
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
        if (!$request->ajax()) { return redirect('/game/'.$url); }
        $game = Game::where('url', '=', $url);
        if ($game->count() > 0) {
            $game = $game->first();
            $players = Player::recentlyCreated( Carbon::createFromTimeStampUTC( $request->input('ts') ), $game->id);
            if ($players->count() > 0) {
                $players = $players->get();
                foreach ($players as $key => $player) {
                    $player->user_data = User::where('id', '=', $player->user_id)->first();
                }
                $keyed = $players->keyBy('user_id');
                $players = $keyed->all();
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
            $players = Player::recentlyUpdated( Carbon::createFromTimeStampUTC( $request->input('ts') ), $game->id);
            if ($players->count() > 0) {
                $players = $players->get();
                foreach ($players as $key => $player) {
                    $player->user_data = User::where('id', '=', $player->user_id)->first();
                }
                $plucked = $players->pluck($request->input('key'), 'user_id');
                $players = $plucked->all();
                $time = Carbon::now()->timestamp;
                return compact('players', 'time');
            }
            return [];
        }
        return;
    }

    /**
     * Handle Join Data.
     *
     * @return Response - True if they are a player, false if a spectator.
     */
    public function postJoin(Request $request, $url) {
        $players = Player::where('user_id', '=', $request->user()->id);
        if ($players->count() > 0) 
            return;
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
                return true;
            }
            return false;
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
            $players = Player::inGameState($request->user()->id, $game->id, $stage)->get();
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
            }
        }
        return;
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
        $this->setPlayersStage($request, $url, 1);
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
        $this->setPlayersStage($request, $url, 2);
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
        $game = Game::where('url', '=', $url);
        if ($game->count() > 0) {
            $game = $game->first();
            $players = Player::where('user_id', '=', $request->user()->id)->where('game_id', '=', $game->id);
            if ($players->count() == 0) return;
            return ['roll' => rand(1, 12)];
        }
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
