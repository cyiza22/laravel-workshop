<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display all the todo</title>
</head>
<body>
    <h1>Todo List</h1>
    <a href="{{ route('todos.create') }}">Create New Todo</a>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach(range(0,10) as $todo)
                <tr>
                    <td>{{$todo}}</td>
                    <td>To do tittle{{$todo}}</td>
                    <td>To do description{{$todo}}</td>
                    <td>
                        <a href="{{ route('todos.show', $todo) }}">View</a>
                        <a href="{{ route('todos.edit', $todo) }}">Edit</a>
                        
                    </td>
                </tr>
            @endforeach
        </tbody>

    
</body>
</html>