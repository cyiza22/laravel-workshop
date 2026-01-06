<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Todo</title>
</head>
<body>
    <h1>Create New Todo</h1>

    <form action="{{ route('todos.store') }}" method="POST">
        @csrf

        <div>
            <label>Title:</label>
            <input type="text" name="title" value="{{ old('title') }}">
            @error('title')
                <div >{{ $message }}</div>
            @enderror

        </div>

        <div>
            <label>Description:</label>
            <textarea name="description" value="{{ old('description') }}"></textarea>
        </div>

        <button type="submit">Create Todo</button>
    </form>
    
</body>
</html>