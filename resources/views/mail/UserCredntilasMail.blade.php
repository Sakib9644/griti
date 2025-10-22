<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Credentials</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
    </style>
</head>
<body style="margin: 0; padding: 0; background-color: #f8fafc; font-family: 'Inter', Arial, sans-serif;">
    <!-- Main Container -->
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f8fafc;">
        <tr>
            <td align="center" style="padding: 40px 0;">
                <!-- Email Container -->
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); overflow: hidden;">
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%); padding: 30px 40px; text-align: center;">
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center">
                                        <h1 style="color: #ffffff; margin: 0; font-size: 24px; font-weight: 600;">Welcome to Our Platform</h1>
                                        <p style="color: rgba(255,255,255,0.8); margin: 10px 0 0; font-size: 16px; font-weight: 400;">Your account has been successfully created</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px;">
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <!-- Introduction -->
                                <tr>
                                    <td style="padding-bottom: 24px;">
                                        <p style="color: #4a5568; line-height: 1.6; margin: 0; font-size: 16px;">
                                            Thank you for joining us! Your account has been created and is ready to use.
                                            Below are your temporary login credentials. Please keep this information secure.
                                        </p>
                                    </td>
                                </tr>

                                <!-- Credentials Box -->
                                <tr>
                                    <td style="padding-bottom: 32px;">
                                        <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f7fafc; border-radius: 8px; border: 1px solid #e2e8f0;">
                                            <tr>
                                                <td style="padding: 24px;">
                                                    <table width="100%" cellpadding="0" cellspacing="0">
                                                        <tr>
                                                            <td style="padding-bottom: 16px;">
                                                                <h3 style="color: #2d3748; margin: 0 0 8px; font-size: 18px; font-weight: 600;">Email Address</h3>
                                                                <p style="color: #4a5568; margin: 0; font-size: 16px; background-color: #edf2f7; padding: 12px; border-radius: 6px;">{{ $email }}</p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <h3 style="color: #2d3748; margin: 0 0 8px; font-size: 18px; font-weight: 600;">Temporary Password</h3>
                                                                <p style="color: #4a5568; margin: 0; font-size: 16px; background-color: #edf2f7; padding: 12px; border-radius: 6px;">{{ $password }}</p>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                <!-- Action Button -->
                                <tr>
                                    <td align="center" style="padding-bottom: 32px;">
                                        <a href="{{ $loginUrl }}" style="display: inline-block; padding: 14px 32px; background-color: #4361ee; color: #ffffff; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 16px; box-shadow: 0 2px 4px rgba(66, 153, 225, 0.3);">Access Your Account</a>
                                    </td>
                                </tr>

                                <!-- Important Note -->
                                <tr>
                                    <td style="padding: 20px; background-color: #fff5f5; border-radius: 8px; border-left: 4px solid #fc8181;">
                                        <table width="100%" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td align="left">
                                                    <h4 style="color: #c53030; margin: 0 0 8px; font-size: 16px; font-weight: 600;">
                                                        <svg style="vertical-align: middle; margin-right: 8px;" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M12 9V11M12 15H12.01M5.07183 19H18.9282C20.4678 19 21.4301 17.3333 20.6603 16L13.7321 4C12.9623 2.66667 11.0377 2.66667 10.2679 4L3.33975 16C2.56995 17.3333 3.53223 19 5.07183 19Z" stroke="#c53030" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                        </svg>
                                                        Security Notice
                                                    </h4>
                                                    <p style="color: #744210; margin: 0; font-size: 14px; line-height: 1.5;">
                                                        For security reasons, please change your password immediately after your first login.
                                                        Do not share these credentials with anyone.
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8fafc; padding: 24px 40px; border-top: 1px solid #e2e8f0;">
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center" style="padding-bottom: 16px;">
                                        <p style="color: #718096; margin: 0; font-size: 14px;">
                                            Need help? Contact our support team at
                                            <a href="mailto:support@company.com" style="color: #4361ee; text-decoration: none;">support@company.com</a>
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center">
                                        <p style="color: #a0aec0; margin: 0; font-size: 12px;">
                                            &copy; 2023 Company Name. All rights reserved.
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
