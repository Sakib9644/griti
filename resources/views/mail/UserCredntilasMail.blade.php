<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Temporary Login Details</title>
</head>
<body style="margin:0; padding:0; background-color:#f7f7f7; font-family: Arial, sans-serif;">
    <table width="100%" height="100%" cellpadding="0" cellspacing="0" style="background-color:#f7f7f7;margin-top:100px">
        <tr>
            <td align="center" valign="middle">
                <table width="600" cellpadding="20" cellspacing="0" style="background-color:#ffffff; border-radius:8px; box-shadow:0 2px 8px rgba(0,0,0,0.1);">
                    <tr>
                        <td align="center">
                            <h2 style="color:#333333; margin-bottom:20px;">Welcome!</h2>
                            <p style="color:#555555; line-height:1.5; margin-bottom:20px;">
                                Your account has been created. Use the credentials below to login:
                            </p>

                            <table cellpadding="10" cellspacing="0" style="background-color:#f0f0f0; border-radius:5px; margin-bottom:20px;">
                                <tr>
                                    <td><strong>Email:</strong> {{ $email }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Password:</strong> {{ $password }}</td>
                                </tr>
                            </table>

                            <p style="margin-bottom:20px;">
                                <a href="{{ $loginUrl }}" style="display:inline-block; padding:12px 20px; background-color:#4CAF50; color:#ffffff; text-decoration:none; border-radius:5px;">Login Now</a>
                            </p>

                            <p style="color:#999999; font-size:12px;">
                                Please change your password after your first login.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
