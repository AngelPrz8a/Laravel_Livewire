<?php

namespace App\Http\Livewire;

use App\Models\Category;
use App\Models\Thread;
use Livewire\Component;

class ShowThreads extends Component
{
    public $category = "";
    public $search = "";

    public function filterByCategory($category){
        $this->category = $category;
    }

    public function render()
    {
        $threads = Thread::query()
        ->where("title","like","%$this->search%");

        if($this->category){
            $threads->where("category_id",$this->category);
        }

        $threads->with("user","category");
        $threads->withCount("replies")
        ->latest();
        
        return view('livewire.show-threads',[
            "categories"=>Category::get(),
            "threads"=> $threads->get(),
        ]);
    }
}
