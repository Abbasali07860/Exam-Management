<!DOCTYPE html>
<html>
<head>
    <title>Exam Results</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { text-align: center; color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #4a5568; color: white; }
        tr:nth-child(even) { background-color: #f7fafc; }
    </style>
</head>
<body>
    <h1>Exam Results</h1>
    <table>
        <thead>
            <tr>
                <th>Exam Name</th>
                <th>Student Name</th>
                <th>Score</th>
                <th>Date Attempted</th>
                <th>Published</th>
            </tr>
        </thead>
        <tbody>
            @foreach($results as $result)
                <tr>
                    <td>{{ $result->exam->title }}</td>
                    <td>{{ $result->user->name }}</td>
                    <td>{{ $result->score }}</td>
                    <td>{{ $result->end_time->format('Y-m-d') }}</td>
                    <td>{{ $result->published ? 'Yes' : 'No' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>