<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Models\ActivityLog;
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

        ActivityLog::create([
            'log_name' => 'Admin Post Created',
            'event' => 'created',
            'user_type' => 'Admin',
            'user_id' => auth('admin')->user()->id,
            'description' => 'You added a new post with the title of ' . $request->title
        ]);

        return $this->success('Update created successfully!');
    }

    public function update(PostRequest $request, $id)
    {
        $update = Update::find($id);

        if(!empty($update)){
            $update->update($request->validated());

            ActivityLog::create([
                'log_name' => 'Admin Post Updated',
                'event' => 'created',
                'user_type' => 'Admin',
                'user_id' => auth('admin')->user()->id,
                'description' => 'You updated a post with the title of ' . $update->title
            ]);
    
            return $this->success('Update saved!');
        } else {
            return $this->error('Update not saved.');
        }
    }

    public function view($id)
    {

        $update = Update::find($id);

        if($update){
            $update->update(['views' => $update->views + 1]);
        }
        // if(!empty($update)){
        //     return response()->json(Update::get());
        // } else {
        //     return $this->error('Update not found');
        // }
    }

    public function destroy($id)
    {
        $update = Update::where('id', $id)->first();

        ActivityLog::create([
            'log_name' => 'Admin Post Deleted',
            'event' => 'created',
            'user_type' => 'Admin',
            'user_id' => auth('admin')->user()->id,
            'description' => 'You deleted a post with the title of ' . $update->title
        ]);
        
        Update::destroy($id);

        return $this->success('Update deleted successfully!');

    }
}
