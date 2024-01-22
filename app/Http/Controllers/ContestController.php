<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use App\Models\Contest;

class ContestController extends BaseController {
    protected $contest;
    public function __construct() {
        parent::__construct();
        $this->middleware(
            function( $request, $next ) {
                $this->contest = Contest::whereDoesntHave( "games" )
                    ->get();
                if( $this->contest ) {
                    return $next($request);
                }
                abort( 404 );
            }
        )->only( [ "edit", "update" ] );
    }
    public function index() {
        $contests = Contest::sortable()
            ->paginate();
        return view( "admin.contest.index" )
            ->with( "contests", $contests );
    }
    public function create() {
        return view( "admin.contest.create" );
    }
    public function store() {
        // ...
    }
    public function edit( Contest $contest ) {
        return view( "admin.contest.edit" )
            ->with( "contest", $contest );
    }
    public function update() {
        // ...
    }
}
