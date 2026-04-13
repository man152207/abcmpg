<?php
use App\Models\Customer;
$customer = Customer::where('phone', $ad->customer)->first();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Reminder</title>
    <style>
        body, table, td, div, p { margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; background-color: #f5f5f5; }
        .email-container { max-width: 600px; margin: 0 auto; background-color: #ffffff; }
        .header { background-color: #2c3e50; padding: 20px; text-align: center; }
        .header img { max-width: 180px; height: auto; display: block; margin: 0 auto; background-color: white; padding: 10px; border-radius: 8px; }
        .content { padding: 25px; text-align: center; }
        .alert { background-color: #f44336; color: white; padding: 15px; font-size: 18px; border-radius: 8px; margin-bottom: 20px; font-weight: 700; }
        .customer-name { color: #2c3e50; font-size: 20px; font-weight: 700; margin-bottom: 15px; }
        .message { line-height: 1.6; color: #555555; margin-bottom: 18px; text-align: left; }
        .payment-amount { background-color: #e3f2fd; border: 2px solid #2196f3; padding: 20px; margin: 20px 0; border-radius: 8px; }
        .amount-label { font-size: 16px; color: #0d47a1; margin-bottom: 10px; }
        .amount { font-size: 32px; color: #e74c3c; font-weight: 700; margin: 0; }
        .buttons-container { margin: 25px 0; text-align: center; }
        .button { display: inline-block; background-color: #007bff; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; margin: 8px; font-weight: 500; min-width: 200px; }
        .button-whatsapp { background-color: #25D366; }
        .button-download { background-color: #28a745; }
        .footer { background-color: #34495e; padding: 25px; text-align: center; color: white; }
        .footer-links { margin-bottom: 15px; }
        .footer-link { color: #bdc3c7; text-decoration: none; margin: 0 10px; font-size: 14px; }
        .footer-info { font-size: 14px; color: #ecf0f1; margin: 5px 0; }
        .copyright { font-size: 12px; color: #95a5a6; margin-top: 15px; }
        @media only screen and (max-width: 600px) {
            .content { padding: 20px; }
            .button { display: block; margin: 10px 0; }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <img src="https://app.mpg.com.np/images/logo.png" alt="MPG Solution">
        </div>
        
        <div class="content">
            <!-- Alert box with warning message -->
            <div class="alert">
                ⚠️ **Action Required**: Your ad campaign is currently in grace period and will be paused soon if payment is not completed.
            </div>
            
            <div class="customer-name">Dear {{ $customer->name }},</div>
            
            <p class="message">
                This is a friendly reminder that payment is required to continue running your ad campaign. Please reload your account at your earliest convenience to ensure uninterrupted service.
            </p>
            
            <div class="payment-amount">
                <div class="amount-label">Amount Due</div>
                <div class="amount">Rs {{ $ad->NRP }}</div>
            </div>
            
            <p class="message">
                Payment can be made through <b>eSewa, Khalti, ImePay, Fonepay, or Direct Deposit</b>. Details have been shared via WhatsApp.
            </p>
            
            <div class="buttons-container">
                <a href="https://wa.me/mpgsolution" class="button button-whatsapp">Contact Us on WhatsApp</a>
                <a href="{{ URL('/receipt/pdf_gen/' . $ad->id) }}" class="button button-download">Download Receipt as PDF</a>
            </div>
            
            <p class="message">
                If you require any assistance, our team is available from 9 AM to 5 PM, Sunday to Friday.
            </p>
        </div>
        
        <div class="footer">
            <div class="footer-links">
                <a href="https://mpg.com.np/" class="footer-link">Visit Our Website</a>
                <a href="https://wa.me/9779856000601" class="footer-link">WhatsApp Support</a>
                <a href="mailto:support@mpg.com.np" class="footer-link">Email Us</a>
            </div>
            
            <div class="footer-info">MPG Solution Private Limited</div>
            <div class="footer-info">Pokhara 10, Amarsingh Chowk, Kaski, 33700 Nepal</div>
            <div class="footer-info">Email: support@mpg.com.np | Phone: +977 9856000601</div>
            
            <div class="copyright">
                © 2017–<?php echo date('Y'); ?> MPG Solution Private Limited. All Rights Reserved.
            </div>
        </div>
    </div>
</body>
</html>
