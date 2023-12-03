<?php

namespace App\Http\Livewire;

use App\Models\Thread;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ShowThread extends Component
{
    public Thread $thread;
    public $body = "";

    public function postReply(){
        //validate
        $this->validate([
            "body"=>"required",
        ]);
        //create
        auth()->user()->replies()->create([
            "thread_id"=>$this->thread->id,
            "body"=>$this->body,
        ]);
        //refresh
        //$this->body="";
        $this->reset("body");
    }

    public function render()
    {
        // return view('livewire.show-thread',[
        //     "replies"=>
        //     DB::table('threads')
        //     ->select("replies")
        //     ->where("threads.id",$this->thread->id)
        //     ->where("users.id",auth()->user()->id)
        //     ->join('replies', 'threads.id', '=', 'replies.thread_id')
        //     ->join('users', 'threads.user_id', '=', "users.id")
        //     ->get(),
        // ]);

        return view('livewire.show-thread',[
            "replies"=>
            $this->thread
            ->replies()
            ->whereNull("reply_id")
            ->with("user","replies.user","replies.replies")
            ->get(),
        ]);

         //    dd(
        //         DB::table('threads')
        //         ->where("threads.id",$this->thread->id)
        //         ->where("users.id",auth()->user()->id)
        //         //->join("replies","replies.reply_id","=","reply.id")
        //         ->join('replies', 'threads.id', '=', 'replies.thread_id')
        //         //->whereNull("reply_id")
        //         ->join('users', 'threads.user_id', '=', "users.id")
        //         ->get()
        //     ); 

        
    }
}
