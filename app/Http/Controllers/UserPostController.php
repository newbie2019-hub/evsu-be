<?php

namespace App\Http\Controllers;

use App\Models\Update;
use Illuminate\Http\Request;

class UserPostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        return response()->json(Update::paginate(10));
    }
    public function view($id)
    {

        $update = Update::find($id);

        if($update){
            $update->update(['views' => $update->views + 1]);
        }
    }
}
