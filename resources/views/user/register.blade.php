<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <!-- Add your custom stylesheets or styles here -->
    <style>
        body {
            background-color: #f8f9fa;
        }

        .register-container {
            max-width: 400px;
            margin: auto;
            margin-top: 50px;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }
    </style>
</head>

<body>

    <div class="register-container">
        <h2 class="text-center mb-4">Register</h2>
        <!-- Your registration form goes here -->
        <form action="{{ route('register') }}" method="post">
            @csrf
            <!-- Add your form fields here -->
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="name">Phone</label>
                <input type="text" name="phone" id="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="password">Confirm Password</label>
                <input type="password" name="password_confirmation" id="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Register</button>
        </form>
    </div>

</body>

</html>