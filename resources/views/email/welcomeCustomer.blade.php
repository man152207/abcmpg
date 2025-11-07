<!-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Our Service</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .header {
            background-color: #3498db;
            color: #fff;
            text-align: center;
            padding: 20px;
        }

        .content {
            padding: 30px;
            text-align: center;
        }

        .content h1 {
            color: #333;
        }

        .content p {
            color: #555;
            line-height: 1.6;
        }

        .footer {
            text-align: center;
            padding: 20px;
            background-color: #f4f4f4;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h2>Welcome to Our Service!</h2>
        </div>

        <div class="content">
            <h1>Hello {{ $customer->name }},</h1>
            <p>Thank you for joining our community. We are thrilled to have you on board!</p>
            <p>Explore our features and make the most of your experience with us.</p>
        </div>

        <div class="footer">
            <p>If you have any questions, feel free to contact our support team.</p>
            <p>Best regards,<br>Our Team</p>
        </div>
    </div>
</body>

</html> -->
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to MPG Solution</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap');

        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #eeeeee;
        }

        .email-container {
            max-width: 600px;
            margin: auto;
            background-color: #ffffff;
            padding: 0px;
            border: 1px solid #dddddd;
        }

        .header {
            background-color: #dce1e9;
            padding: 6px;
            text-align: center;
        }

        .header img {
            max-width: 180px;
        }

        .content {
            padding: 20px;
            text-align: center;
        }

        .content h2 {
            color: #0047ab;
        }

        .content p {
            line-height: 1.5;
            color: #333333;
        }

        .footer {
            background-color: #f2f2f2;
            padding: 10px;
            text-align: center;
            font-size: 12px;
            color: #666666;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="header">
            <img src="https://app.mpg.com.np/images/logo.png" alt="MPG Solution Private Limited Logo">
        </div>
        <div class="content">
            <h2>Welcome to MPG Solution!</h2>
            <p>Dear {{ $customer->name }},</p>
            <p>We are excited to have you on board. As a leading social media advertising agency, we're dedicated to helping your business reach its full potential online.</p>
            <p>Our team of experts is committed to crafting campaigns that resonate with your audience and drive results. We believe in the power of social media to connect, engage, and convert.</p>
            <p>Thank you for choosing MPG Solution. Let's make your brand stand out together!</p>
            <p>Warm regards,</p>
            <p>The MPG Solution Team</p>
        </div>
        <div class="footer">
            <p>MPG Solution Private Limited</p>
            <p>Pokhara 15, Kaski, Nepal</p>
            <p>Email: <a href="mailto:support@mpg.com.np">support@mpg.com.np</a></p>
            <p>Website: <a href="www.mpg.com.np">www.mpg.com.np</a></p>
            <p>© 2017–{{ date('Y') }} MPG Solution Private Limited. All Rights Reserved.</p>
        </div>
    </div>
</body>

</html>