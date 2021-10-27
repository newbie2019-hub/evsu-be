<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Models\Update;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        return response()->json(Update::paginate(10));
    }

    public function store(PostRequest $request)
    {
        Update::create($request->validated());
        return $this->success('Update created successfully!');
    }

    public function update(PostRequest $request, $id)
    {
        $update = Update::find($id);

        if(!empty($update)){
            $update->update($request->validated());
            return $this->success('Update saved!');
        } else {
            return $this->error('Update not saved.');
        }
    }

    public function view($id)
    {
        $update = Update::find($id);

        if(!empty($update)){
            return response()->json(Update::get());
        } else {
            return $this->error('Update not fouind.');
        }
    }

    public function destroy($id)
    {
        Update::destroy($id);
        return $this->success('Update deleted successfully!');

    }
}
