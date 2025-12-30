<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Trial Reminder</title>
    <style>
        .button {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            color: #fff;
            background-color: #3490dc;
            text-decoration: none;
            border-radius: 5px;
        }

        .container {
            font-family: Arial, sans-serif;
            line-height: 1.5;
            color: #333;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Hello {{ $user->name }},</h1>

        <p>Your <strong>3-day trial</strong> will end on <strong>{{ $trialEndDate }}</strong>.</p>

        <p>To continue enjoying our services without interruption, please pay for your Stripe plan.</p>

        <p>Thanks,<br>{{ config('app.name') }}</p>
    </div>
</body>

</html>
