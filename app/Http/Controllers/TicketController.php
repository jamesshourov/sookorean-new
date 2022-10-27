<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TicketController extends Controller
{
    public function all(Request $request)
    {
        $rows = DB::table('tickets')
                ->orderBy('id', 'desc')
                ->paginate(50);
        return view('ticket.index', compact('rows'));
    }
}
