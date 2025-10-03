<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Home</title>
    <style>
        body {
            font-family: system-ui, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        h1 {
            margin-bottom: 1rem;
        }

        a.button {
            display: inline-block;
            padding: .75rem 1.25rem;
            background: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: .5rem;
            font-size: 1rem;
        }

        a.button:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
<h1>Welcome to the Contacts</h1>
<p>This is a simple Laravel + HTML project for managing contacts.</p>
<a href="{{ url('/contacts') }}" class="button">Go to Contacts</a>
</body>
</html>
