<?php

namespace App\Http\Livewire;

use App\Models\Reply;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class ShowReply extends Component
{
    use AuthorizesRequests;

    public Reply $reply;
    public $body = "";
    public $is_creating = false;
    public $is_editing = false;

    protected $listeners = [
        'refreshComponent' => '$refresh'
    ];


    public function updatedIsCreating(){
        $this->is_editing = false;
        $this->body = "";
    }

    public function updatedIsEditing(){
        $this->authorize("update",$this->reply);
        $this->is_creating = false;
        $this->body = $this->reply->body;
    }

    public function updateReply(){
        $this->authorize("update",$this->reply);

        //validate
        $this->validate([
            "body"=>"required",
        ]);

        //updatee
        $this->reply->update([
            "body"=>$this->body,
        ]);

        //refresh
        $this->is_editing = false;
    }

    public function postChild(){

        //Evita responder en una cadena de respuestas
        if(!is_null($this->reply->reply_id)) return;

        //validate
        $this->validate([
            "body"=>"required",
        ]);

        //create
        auth()->user()->replies()->create([
            "reply_id"=>$this->reply->id,
            "thread_id"=>$this->reply->thread->id,
            "body"=>$this->body,
        ]);

        //refresh
        $this->is_creating = false;
        $this->reset("body");
        $this->emitSelf('refreshComponent');
    }
    
    public function render()
    {
        return view('livewire.show-reply'); 
    }
}
