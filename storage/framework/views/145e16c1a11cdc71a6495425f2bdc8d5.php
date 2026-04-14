<?php
use App\Models\Customer;
$customer = Customer::where('phone', $ad->customer)->first();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ad Campaign Applied</title>
    <style>
        body, table, td, div, p { margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; background-color: #f5f5f5; }
        .email-container { max-width: 600px; margin: 0 auto; background-color: #ffffff; }
        .header { background-color: #2c3e50; padding: 20px; text-align: center; }
        .header img { max-width: 180px; height: auto; display: block; margin: 0 auto; background-color: white; padding: 10px; border-radius: 8px; }
        .content { padding: 25px; text-align: center; }
        .success-badge { background-color: #0047ab; color: white; padding: 12px 25px; border-radius: 25px; display: inline-block; margin-bottom: 20px; font-weight: 700; font-size: 18px; }
        .customer-name { color: #2c3e50; font-size: 20px; font-weight: 700; margin-bottom: 15px; }
        .message { line-height: 1.6; color: #555555; margin-bottom: 18px; text-align: left; }
        .deadline-section { background-color: #fff8e1; border: 2px solid #ffa000; padding: 15px; margin: 20px 0; border-radius: 8px; text-align: center; }
        .deadline-title { color: #ff6f00; font-weight: 700; margin-bottom: 5px; font-size: 16px; }
        .deadline-time { color: #e65100; font-weight: 700; font-size: 18px; margin: 0; }
        .payment-amount { background-color: #e3f2fd; border: 2px solid #2196f3; padding: 20px; margin: 20px 0; border-radius: 8px; }
        .amount-label { font-size: 16px; color: #0d47a1; margin-bottom: 10px; }
        .amount { font-size: 32px; color: #e74c3c; font-weight: 700; margin: 0; }
        .payment-methods { background-color: #e8f5e9; border: 1px solid #4caf50; padding: 18px; margin: 20px 0; border-radius: 8px; text-align: left; }
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
            <div class="success-badge">Ad Campaign Successfully Applied</div>
            
            <div class="customer-name">Dear <?php echo e($customer->name); ?>,</div>
            
            <p class="message">
                Thank you for choosing MPG Solution! We're delighted to inform you that your social media campaign has been successfully set up and is ready to launch.
            </p>
            
            <div class="deadline-section">
                <div class="deadline-title">Kind Reminder</div>
                <div class="deadline-time">Please complete payment within 3 hours</div>
            </div>
            
            <p class="message">
                To activate your campaign and begin reaching your target audience, we kindly request you to complete the payment process. Your campaign will start immediately after we receive payment confirmation.
            </p>
            
            <div class="payment-amount">
                <div class="amount-label">Campaign Amount</div>
                <div class="amount">Rs <?php echo e($ad->NRP); ?></div>
            </div>
            
            <div class="payment-methods">
                <h3 style="color:#2e7d32; margin:0 0 12px 0; font-size: 18px;">Preferred Payment Options</h3>
                <p class="message" style="margin:0;">
                    • <strong>QR Code Payment</strong> (sent via WhatsApp)<br>
                    • Digital Wallets: eSewa, Khalti, ImePay<br>
                    • Fonepay & Bank Transfer<br>
                    <br>
                    <em>Full payment is required to begin campaign delivery</em>
                </p>
            </div>
            
            <div class="buttons-container">
                <a href="https://wa.me/9779856000601" class="button button-whatsapp">Get Payment Assistance</a>
                <a href="<?php echo e(URL('/receipt/pdf_gen/' . $ad->id)); ?>" class="button button-download">Download Payment Receipt</a>
            </div>
            
            <p class="message">
                Should you require any assistance with the payment process, our support team is here to help you from 9 AM to 5 PM, Sunday through Friday.
            </p>
            
            <p class="message" style="text-align: center; font-style: italic; color: #666;">
                We look forward to helping your business grow through effective social media marketing!
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
</html><?php /**PATH /home/mpgcomnp/app.mpg.com.np/resources/views/email/ad-receipt.blade.php ENDPATH**/ ?>