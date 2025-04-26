<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>404 - Page Not Found | Toolzy</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, follow">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            background-color: #ffffff; /* Toolzy's background color */
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            text-align: center;
            padding: 20px;
            height: 100vh;
        }

        .image-container {
            max-width: 90%;
            width: 600px;
        }

        .image-container img {
            width: 100%;
            height: auto;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }
        .message h1 {
            font-size: 28px;
            color: #0d6efd;
            margin-bottom: 8px;
        }

        .message p {
            font-size: 16px;
            color: #6c757d;
            margin-bottom: 24px;
        }

        .home-button {
            padding: 12px 24px;
            background-color: #0d6efd;
            color: white;
            font-weight: bold;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .home-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <div class="image-container">
        <img src="{{ asset('images/404.webp') }}" alt="404 Page not found">
    </div>

    <div class="message">
        <h1>Oops! Page not found</h1>
        <p>It seems you've wandered off the path. Let’s get you back home.</p>
        <a href="{{ url('/') }}" class="home-button">Back to Home</a>
    </div>

</body>
</html>