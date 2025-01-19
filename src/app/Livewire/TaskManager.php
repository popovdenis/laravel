<?php
namespace App\Livewire;

use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;
use App\Models\Task;

class TaskManager extends Component
{
    public Collection $tasks;
    public $taskName;

    public function mount()
    {
        $this->tasks = Task::all();
    }

    public function addTask()
    {
        $this->validate(['taskName' => 'required|string|min:3']);
        Task::create(['name' => $this->taskName]);
        $this->tasks = Task::all();
        $this->taskName = null;
    }

    public function render()
    {
        return view('livewire.task-manager')->layout('layouts.app');
    }
}
