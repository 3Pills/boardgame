<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Game;

class GamesController extends Controller {
    public function index() {
    	//$games = Game::All();
    	$games = [
    		"dix"=>"123"
    	];
    	return view('games.index', compact('games'));
    }
    public function show($id){
    	return $id;
    }
}
