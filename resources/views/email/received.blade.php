<?php

use App\Models\Customer;

$customer = Customer::where('phone', $ad->customer)->first();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Confirmation</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap');
        
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }
        
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
        }
        
        .header {
            background-color: #2c3e50;
            padding: 20px;
            text-align: center;
        }
        
        .header img {
            max-width: 180px;
            height: auto;
            display: block;
            margin: 0 auto;
            background-color: white;
            padding: 10px;
            border-radius: 8px;
        }
        
        .content {
            padding: 30px;
            text-align: center;
        }
        
        .success-badge {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border-radius: 25px;
            display: inline-block;
            margin-bottom: 20px;
            font-weight: 700;
            font-size: 18px;
        }
        
        .customer-name {
            color: #2c3e50;
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 15px;
        }
        
        .message {
            line-height: 1.6;
            color: #555555;
            margin-bottom: 20px;
            text-align: left;
        }
        
        .payment-amount {
            background-color: #fff3cd;
            border: 2px solid #ffc107;
            padding: 20px;
            margin: 25px 0;
            border-radius: 10px;
        }
        
        .amount-label {
            font-size: 16px;
            color: #856404;
            margin-bottom: 10px;
        }
        
        .amount {
            font-size: 32px;
            color: #e74c3c;
            font-weight: 700;
            margin: 0;
        }
        
        .buttons-container {
            margin: 30px 0;
            text-align: center;
        }
        
        .button {
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 5px;
            margin: 5px 10px;
            font-weight: 500;
            min-width: 180px;
        }
        
        .button-whatsapp {
            background-color: #25D366;
        }
        
        .button-website {
            background-color: #3498db;
        }
        
        .button-review {
            background-color: #ff6b6b;
        }
        
        .review-section {
            background-color: #f8f9fa;
            padding: 20px;
            margin: 25px 0;
            border-radius: 8px;
            border-left: 4px solid #6c757d;
        }
        
        .review-title {
            color: #2c3e50;
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .footer {
            background-color: #34495e;
            padding: 25px;
            text-align: center;
            color: white;
        }
        
        .footer-links {
            margin-bottom: 15px;
        }
        
        .footer-link {
            color: #bdc3c7;
            text-decoration: none;
            margin: 0 10px;
            font-size: 14px;
        }
        
        .footer-info {
            font-size: 14px;
            color: #ecf0f1;
            margin: 5px 0;
        }
        
        .copyright {
            font-size: 12px;
            color: #95a5a6;
            margin-top: 15px;
        }
        
        @media only screen and (max-width: 600px) {
            .content {
                padding: 20px;
            }
            
            .button {
                display: block;
                margin: 10px 0;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <img src="https://app.mpg.com.np/images/logo.png" alt="MPG Solution Private Limited Logo">
        </div>
        
        <div class="content">
            <div class="success-badge">Payment Successfully Received!</div>
            
            <div class="customer-name">Dear {{ $customer->name }},</div>
            
            <p class="message">
                We're thrilled to inform you that we've successfully received your payment for the social media campaign. 
                Your campaign has been activated and is now running across all selected platforms.
            </p>
            
            <p class="message">
                Thank you for choosing MPG Solution. We're committed to delivering outstanding results 
                for your campaign and helping your business grow.
            </p>
            
            <div class="payment-amount">
                <div class="amount-label">Payment Amount Received</div>
                <div class="amount">Rs {{ $ad->NRP }}</div>
            </div>
            
            <div class="buttons-container">
                <a href="https://wa.me/mpgsolution" class="button button-whatsapp">Contact on WhatsApp</a>
                <a href="https://mpg.com.np/" class="button button-website">Visit Our Website</a>
            </div>
            
            <div class="review-section">
                <div class="review-title">Share Your Experience</div>
                <p class="message" style="text-align: center;">
                    Help other businesses discover our services by leaving a review on Google.
                </p>
                <div class="buttons-container">
                    <a href="https://g.page/r/CQsP7NDI6PELEAI/review" class="button button-review">Write a Google Review</a>
                </div>
            </div>
            
            <p class="message">
                Our support team is available from 9 AM to 5 PM, Sunday to Friday. 
                Don't hesitate to reach out if you need any assistance with your campaign.
            </p>
        </div>
        
        <div class="footer">
            <div class="footer-links">
                <a href="https://mpg.com.np/" class="footer-link">Website</a>
                <a href="https://wa.me/mpgsolution" class="footer-link">WhatsApp</a>
                <a href="mailto:support@mpg.com.np" class="footer-link">Email</a>
            </div>
            
            <div class="footer-info">MPG Solution Private Limited</div>
            <div class="footer-info">Pokhara 10, Amarsingh Chowk, Kaski, Nepal</div>
            <div class="footer-info">Email: support@mpg.com.np</div>
            
            <div class="copyright">
                © 2017–<?php echo date('Y'); ?> MPG Solution Private Limited. All Rights Reserved.
            </div>
        </div>
    </div>
</body>
</html>