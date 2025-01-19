<div>
    <h1>Task Manager</h1>
    <form wire:submit.prevent="addTask">
        <input type="text" wire:model="taskName" placeholder="Enter task name">
        <button type="submit" style="color: #c5d7f2">Add Task</button>
    </form>
    @error('taskName') <p style="color: red;">{{ $message }}</p> @enderror

    <ul style="color: #c5d7f2">
        @foreach ($tasks as $task)
            <li>{{ $task->name }}</li>
        @endforeach
    </ul>
</div>
