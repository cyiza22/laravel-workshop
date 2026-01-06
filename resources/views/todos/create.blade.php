<!DOCTYPE html>
<html>
<head>
    <title>Create Todo</title>
</head>
<body>
    <h1>Create New Todo</h1>
    
    <form action="{{ route('todos.index') }}" method="POST">
        @csrf
        
        <div>
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" required>
        </div>
        
        <br>
        
        <div>
            <label for="description">Description:</label>
            <textarea id="description" name="description" rows="4"></textarea>
        </div>
        
        <br>
        
        <button type="submit">Save Todo</button>
    </form>
    
    <br>
</body>
</html>