<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel App - Login</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: #f5f5f5;
            min-height: 100vh;
            width: 100%;
        }
        
        .login-container {
            background: white;
            padding: 2rem;
            min-height: 100vh;
            width: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        
        .login-box {
            width: 100%;
            max-width: 400px;
            padding: 0 20px;
        }
        
        .input-field {
            width: 100%;
            padding: 0.75rem;
            margin: 0.5rem 0;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
        }
        
        .btn {
            width: 100%;
            padding: 0.75rem;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            margin-top: 1rem;
        }
        
        .header {
            text-align: center;
            margin-bottom: 2rem;
            width: 100%;
        }

        .logo {
            font-size: 2rem;
            margin-bottom: 1rem;
        }

        form {
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <div class="header">
                <div class="logo">✈️</div>
                <h1>Travel App Login</h1>
            </div>
            <form action="main.php">
                <input type="text" class="input-field" placeholder="Username" value="demo@example.com">
                <input type="password" class="input-field" placeholder="Password" value="password">
                <button type="submit" class="btn">Login</button>
            </form>
        </div>
    </div>
</body>
</html>