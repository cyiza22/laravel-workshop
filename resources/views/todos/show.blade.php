<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>show blade</title>
</head>
<body>
    <h1>Todo Details</h1>
    <p><strong>Title</strong>
        {{ $todo->title }}
    </p>
    <p><strong>Description</strong>
        {{ $todo->description }} 
    </p>
    <p><strong>Status</strong>
        {{ $todo->is_completed ? 'Completed' : 'Pending' }}
    </p>
    <a href="{{ route('todos.index') }}">Back to Todo List</a>
    
    
    
    
</body>
</html>