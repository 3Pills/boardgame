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
     * Create a new user controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }    

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
        return view('games.game', compact('url'));
    }


    public function roll(Request $request, $url) {
        return rand(1, 11);
    }

    public function chat(Request $request, $url) {
        GameMessage::create(['user_id' => $request->user()->id, 'game_id' => Game::where('url', '=', $url)->first()->id, 'msg' => $request->input('msg')]);
        return $request->user()->name;
    }

    public function getChat(Request $request, $url) {
        //dd(Carbon::parse("1970-01-01T11:00:00"));
        $game = Game::where('url', '=', $url)->first();
        if ($game) {
            //$msgList = GameMessage::where('game_id', '=', $game->id)->where( 'created_at', '>', Carbon::parse(($request->input('latest_chat'))) )->get();
            $msgList = GameMessage::where('game_id', '=', $game->id)->get();
            if ($msgList->count()) {
                $data = $msgList->toArray();
                $data['user_names'] = [];
                foreach ($msgList as $num => $msgData) {
                    if (!array_key_exists($msgData->user_id, $data['user_names'])) {
                        $data['user_names'][$msgData->user_id] = User::where('id', '=', $msgData->user_id)->first()->name;
                    }
                    # code...
                }
                //$data = [
                //    [
                //        "user" => $request->user()->name, 
                //        "msg" => "PONG",
                //        "time" => Carbon::now()->toTimeString()
                //    ],
                //];
                return $data;//.":".strval($time->minute);
            }
        }
    }
}
