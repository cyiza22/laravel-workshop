<!DOCTYPE html>
<html>
<head>
    <title>Edit Todo</title>
</head>
<body>
    <h1>Edit Todo #{{ $id }}</h1>
    
    <form action="{{ route('todos.update', $id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div>
            <label>Title:</label>
            <input type="text" name="title" required>
        </div>
        
        <div>
            <label>Description:</label>
            <textarea name="description"></textarea>
        </div>
        
        <button type="submit">Update Todo</button>
    </form>
    
    <a href="{{ route('todos.index') }}">Back to List</a>
</body>
</html>