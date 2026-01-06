<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Page</title>
</head>
<body>
    <h1>Edit Todo</h1>

    <form action="{{ route('todos.update', $todo->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div>
            <label>Title:</label>
            <input type="text" name="title" value="{{ old('title', $todo->title) }}">
            @error('title')
                <div>{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label>Description:</label>
            <textarea name="description">{{ old('description', $todo->description) }}</textarea>
        </div>

        <div>
            <label>
                <input type="checkbox" name="is_completed" {{ old('is_completed', $todo->is_completed) ? 'checked' : '' }}>
                Completed
            </label>
        </div>

        <button type="submit">Update Todo</button>
    </form>
    
</body>
</html>