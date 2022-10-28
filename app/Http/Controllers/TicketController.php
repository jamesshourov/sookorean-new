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
        return view('tickets.index', compact('rows'));
    }
    public function view($id)
    {
        $row = DB::table('tickets')
            ->where('id', $id)
            ->first();
        return view('tickets.details', compact('row'));
    }
    public function update(Request $request)
    {
        $data = [
            'status' => $request->status,
        ];
        $saved = DB::table('tickets')->where('id', $request->id)->update($data);
        if ($saved) {
            return redirect()->back()->with('success', 'Successfully updated!');
        } else {
            return redirect()->back()->with('error', 'Something went wrong!');
        }
    }
}
