<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Temporary Login Details</title>
</head>
<body>
    <h2>Welcome!</h2>
    <p>Your account has been created. Use the credentials below to login:</p>

    <p><strong>Email:</strong> {{ $email }}</p>
    <p><strong>Password:</strong> {{ $password }}</p>

    <p>Login here: <a href="{{ $loginUrl }}">{{ $loginUrl }}</a></p>

    <p>Please change your password after your first login.</p>
</body>
</html>
