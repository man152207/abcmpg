<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Quotation PDF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            position: relative;
        }

        /* Watermark opacity on every page */
        .watermark {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            opacity: 0.05;
            background-image: url('<?php echo e(asset('uploads/tasbirs/watermark.png')); ?>');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .header, .footer {
            text-align: center;
            margin-bottom: 20px;
        }

        .header img {
            max-width: 150px;
            margin-bottom: 10px;
        }

        h1 {
            font-size: 28px;
            color: #007BFF;
            margin: 10px 0;
        }

        h2 {
            font-size: 22px;
            color: #0056b3;
            margin-top: 20px;
        }

        h3 {
            font-size: 18px;
            color: #004085;
            margin-top: 15px;
        }

        p, ul {
            text-align: justify;
            line-height: 1.4; /* Tighter line height */
            margin: 8px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        table, th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f8f9fa;
        }

        /* Adjusted Contact and Signature Section */
        .contact-signature {
            display: flex;
            justify-content: center; /* Center align the entire section */
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #ccc;
        }
        
        .contact-section {
            width: 40%; /* Set width for contact section */
            text-align: left;
            font-size: 12px;
            margin-right: 20px; /* Add margin to space it from the signature section */
        }
        
        .signature-section {
            text-align: center; /* Center align the text in the signature section */
            font-size: 14px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        
        .signature-section .stamp {
            max-width: 150px;
            margin-bottom: 10px;
        }
        
        .signature-section .sign {
            max-width: 80px;
            margin-bottom: 10px;
        }
        
        .signature-section p {
            margin: 0;
            font-weight: bold;
            text-align: center;
        }


    </style>
</head>
<body>

    <div class="watermark"></div> <!-- Watermark will appear on every page -->

    <!-- Header Section -->
    <div class="header">
        <img src="<?php echo e(asset('uploads/tasbirs/logofoqu.png')); ?>" alt="MPG Solution Logo">
        <h1>MPG Solution</h1>
        <p>Pokhara 15, Nayagaun Kaski<br>Pokhara, Kaski, Nepal<br>+977 9856000601<br><a href="mailto:info@adsmpg.com">info@adsmpg.com</a></p>
        <hr>
    </div>

    <!-- Customer Details Section -->
    <div class="content">
        <h2>Customer Details</h2>
        <p><strong>ID:</strong> <?php echo e($quotation->id); ?></p>
        <p><strong>Customer Name:</strong> <?php echo e($quotation->customer_name); ?></p>
        <p><strong>Company:</strong> <?php echo e($quotation->company); ?></p>
        <p><strong>Email:</strong> <?php echo e($quotation->email); ?></p>
        <p><strong>Phone:</strong> <?php echo e($quotation->phone); ?></p>
        <p><strong>Address:</strong> <?php echo e($quotation->address); ?></p>

        <h2>Quotation for <?php echo e($quotation->service_details); ?></h2>

        <h2>Introduction</h2>
        <p>MPG Solution is a dedicated social media marketing company that specializes in advertising your prepared content across major social media platforms, including Facebook and Instagram. We ensure that you receive inquiries and leads directly through Messenger, Instagram, and WhatsApp.</p>
        <p>Our company is registered at Pokhara-15, Nayagaun, and we offer virtual services across Nepal. We are committed to providing unlimited collaboration opportunities through the power of virtual technology.</p>
        <p>Founded in 2017, MPG Solution has since expanded its presence internationally, with offices in Nepal, Canada, and the USA. We combine international quality standards with a deep understanding of the local market to deliver unparalleled results.</p>

        <h2>Quotation Details</h2>

        <h3>1. Service Overview:</h3>
        <ul>
            <li><strong>Platform Advertising:</strong> Advertisement and promotion of your prepared content on Facebook and Instagram.</li>
            <li><strong>Lead Generation:</strong> We ensure that potential customers can easily inquire about your products/services through Messenger, Instagram, and WhatsApp.</li>
            <li><strong>Content Strategy & Consultation:</strong> Consultation on content strategy and optimization to maximize engagement and conversion rates.</li>
        </ul>

        <h3>2. Pricing:</h3>
        <p>Here is the price list you can choose for Social Media Advertisement:</p>
        <ul>
            <li><strong>Rate:</strong> Rs 160 per Unit of engagement (reach, clicks, interactions, etc.)</li>
            <li><strong>Minimum Budget Package:</strong> Suitable for small campaigns with daily visibility.</li>
            <li><strong>Medium Budget Package:</strong> Optimized for mid-sized campaigns with enhanced engagement.</li>
            <li><strong>High Budget Package:</strong> Designed for large campaigns with maximum reach and interactions.</li>
        </ul>

        <h3>3. Target Audience Details:</h3>
        <p><strong>Target Location:</strong> <?php echo e($quotation->target_location); ?></p>
        <p><strong>Age Range:</strong> <?php echo e($quotation->age_range); ?></p>
        <p><strong>Gender:</strong> <?php echo e(ucfirst($quotation->gender)); ?></p>

        <h3>4. Campaign Objectives:</h3>
        <?php if($quotation->service_details == 'Platform Advertising' || $quotation->service_details == 'Lead Generation'): ?>
        <p><?php echo e($quotation->campaign_objectives); ?></p>

        <!-- Estimated Results -->
        <h3>5. Estimated Results</h3>
        <p><strong>Estimated Reach:</strong> <?php echo e(number_format(($quotation->budget / 160) * 10000)); ?> people</p>
        <p><strong>Estimated Interactions:</strong> <?php echo e(number_format(($quotation->budget / 160) * 500)); ?> interactions</p>

        <p>
            <?php if($quotation->campaign_objectives == 'Brand Awareness'): ?>
                The objective is to increase your brand’s visibility among your target audience on Facebook. We aim to maximize reach and impressions, ensuring your brand is noticed by potential customers.
                <strong>Estimated Results:</strong> Based on your selected budget of Rs <?php echo e(number_format($quotation->budget, 2)); ?> over <?php echo e($quotation->duration); ?> days, we estimate reaching between <?php echo e(number_format(($quotation->budget / 160) * 5000)); ?> and <?php echo e(number_format(($quotation->budget / 160) * 10000)); ?> users.
            <?php elseif($quotation->campaign_objectives == 'Reach'): ?>
                The goal is to reach the maximum number of people within your target audience, ensuring that your brand gains widespread visibility.
                <strong>Estimated Results:</strong> With a budget of Rs <?php echo e(number_format($quotation->budget, 2)); ?> over <?php echo e($quotation->duration); ?> days, we estimate reaching <?php echo e(number_format(($quotation->budget / 160) * 6000)); ?> to <?php echo e(number_format(($quotation->budget / 160) * 12000)); ?> people.
            <?php elseif($quotation->campaign_objectives == 'Traffic'): ?>
                This objective is aimed at directing traffic from Facebook to your website, increasing the number of visitors and potential conversions.
                <strong>Estimated Results:</strong> Expect <?php echo e(number_format(($quotation->budget / 160) * 300)); ?> to <?php echo e(number_format(($quotation->budget / 160) * 900)); ?> clicks to your website with a budget of Rs <?php echo e(number_format($quotation->budget, 2)); ?> over <?php echo e($quotation->duration); ?> days.
            <?php elseif($quotation->campaign_objectives == 'Engagement'): ?>
                The focus is on driving user interaction with your content, including likes, comments, shares, and more. We want to create a community around your brand on Facebook.
                <strong>Estimated Results:</strong> Achieve <?php echo e(number_format(($quotation->budget / 160) * 1200)); ?> to <?php echo e(number_format(($quotation->budget / 160) * 3000)); ?> interactions with a budget of Rs <?php echo e(number_format($quotation->budget, 2)); ?> over <?php echo e($quotation->duration); ?> days.
            <?php elseif($quotation->campaign_objectives == 'App Installs'): ?>
                The objective is to encourage users to download and install your mobile application via targeted ads on Facebook.
                <strong>Estimated Results:</strong> Drive <?php echo e(number_format(($quotation->budget / 160) * 100)); ?> to <?php echo e(number_format(($quotation->budget / 160) * 200)); ?> app installs with a budget of Rs <?php echo e(number_format($quotation->budget, 2)); ?> over <?php echo e($quotation->duration); ?> days.
            <?php elseif($quotation->campaign_objectives == 'Video Views'): ?>
                The aim is to increase views and engagement on your video content, making it more visible and engaging to your target audience.
                <strong>Estimated Results:</strong> Attain <?php echo e(number_format(($quotation->budget / 160) * 10000)); ?> to <?php echo e(number_format(($quotation->budget / 160) * 20000)); ?> video views with a budget of Rs <?php echo e(number_format($quotation->budget, 2)); ?> over <?php echo e($quotation->duration); ?> days.
            <?php elseif($quotation->campaign_objectives == 'Lead Generation'): ?>
                The goal is to generate high-quality leads for your business directly through Facebook's lead ads. This includes collecting contact details of potential clients who show interest in your products or services.
                <strong>Estimated Results:</strong> Capture <?php echo e(number_format(($quotation->budget / 160) * 100)); ?> to <?php echo e(number_format(($quotation->budget / 160) * 200)); ?> leads per week with a budget of Rs <?php echo e(number_format($quotation->budget, 2)); ?> over <?php echo e($quotation->duration); ?> days.
            <?php elseif($quotation->campaign_objectives == 'Conversions'): ?>
                Focus on driving valuable conversions on your website, such as purchases, sign-ups, or other actions that align with your business goals.
                <strong>Estimated Results:</strong> Generate <?php echo e(number_format(($quotation->budget / 160) * 50)); ?> to <?php echo e(number_format(($quotation->budget / 160) * 100)); ?> conversions per month with a budget of Rs <?php echo e(number_format($quotation->budget, 2)); ?> over <?php echo e($quotation->duration); ?> days.
            <?php elseif($quotation->campaign_objectives == 'Store Traffic'): ?>
                The aim is to drive foot traffic to your physical store locations, using localized targeting to reach nearby customers.
                <strong>Estimated Results:</strong> Expect an increase in store visits by 20% to 30% with a budget of Rs <?php echo e(number_format($quotation->budget, 2)); ?> over <?php echo e($quotation->duration); ?> days.
            <?php elseif($quotation->campaign_objectives == 'Get More Messages'): ?>
                The objective is to increase the number of direct interactions your business receives through messaging platforms like Facebook Messenger, WhatsApp, and Instagram Direct. This can lead to more personalized customer engagement and higher conversion rates.
                <strong>Estimated Results:</strong> Expect to receive <?php echo e(number_format(($quotation->budget / 160) * 2)); ?> to <?php echo e(number_format(($quotation->budget / 160) * 6)); ?> additional messages per day, with an increase in response rate and customer satisfaction. Based on a budget of Rs <?php echo e(number_format($quotation->budget, 2)); ?> over <?php echo e($quotation->duration); ?> days, your ad could reach <?php echo e(number_format(($quotation->budget / 160) * 9000)); ?> to <?php echo e(number_format(($quotation->budget / 160) * 26000)); ?> accounts.
            <?php endif; ?>
        </p>
        <?php elseif($quotation->service_details == 'Content Strategy & Consultation' || $quotation->service_details == 'Graphic Design' || $quotation->service_details == 'Video Making and Editing'): ?>
        <h3>5. Service Objectives</h3>
        <p>Based on your selected service of <?php echo e($quotation->service_details); ?>, the focus will be on providing tailored strategies and designs that align with your brand's vision and goals.</p>
        <ul>
            <?php if($quotation->service_details == 'Content Strategy & Consultation'): ?>
                <li>We will develop a comprehensive content strategy that enhances your brand's online presence.</li>
                <li>Consultation sessions will focus on optimizing your content for better engagement.</li>
            <?php elseif($quotation->service_details == 'Graphic Design'): ?>
                <li>Custom graphic designs will be created to visually communicate your brand's message effectively.</li>
                <li>Our design approach ensures that your visuals are both eye-catching and aligned with your brand identity.</li>
            <?php elseif($quotation->service_details == 'Video Making and Editing'): ?>
                <li>High-quality video production and editing services will be provided to showcase your brand’s story.</li>
                <li>We focus on creating engaging video content that resonates with your target audience.</li>
            <?php endif; ?>
        </ul>
        <?php endif; ?>

        <h3>6. Advertisement Details</h3>
        <p>In today's hyper-connected world, having a robust online presence isn't just a luxury, but a necessity. MPG Solution understands the intricacies of the digital landscape and the potential it holds for businesses. We're presenting this proposal with a structured approach to give your company a competitive edge in a bustling marketplace.</p>

        <h3>7. Advertising Strategy</h3>
        <ul>
            <li>Understanding Your Business: Before embarking on any campaign, our team will engage in an intensive understanding phase, diving deep into what makes your company unique.</li>
            <li>Audience Profiling: The market is diverse. We don't just target everyone but focus on those who matter, ensuring higher conversion rates.</li>
            <li>Ad Placement and Scheduling: We ensure your ads are seen at the right places and times, maximizing visibility and engagement.</li>
            <li>Feedback Loop: Our strategy isn't static. Using real-time analytics, we refine our approach continually to ensure optimal results.</li>
        </ul>

        <h3>8. Investment & Transparency</h3>
        <ul>
            <li>Transparent Pricing: A clear breakdown of costs ensures there are no hidden fees. Budget can be set according to the content requirement.</li>
            <li>ROI Focused: Our campaigns prioritize actions that drive returns on your investment, ensuring you get more out of what you spend.</li>
            <li>Flexible Packages: We suggest discussing budget and duration with our team. We are open to adjustments based on your feedback and requirements.</li>
        </ul>

        <h3>9. Feedback & Continuous Improvement</h3>
        <ul>
            <li>Weekly Analytics Report: A comprehensive breakdown of all metrics, from engagement rates to conversion percentages.</li>
            <li>Quarterly Strategy Review: Every quarter, we take a step back, evaluating our broader strategy, and making significant tweaks if required.</li>
            <li>Customer Feedback Loop: Your feedback is valuable. Regular check-ins ensure we're aligned with your vision and expectations.</li>
        </ul>

        <h3>10. Service Terms</h3>
        <p>Before we start, just get familiar with our terms. Read the full terms from this link: <a href="https://mpg.com.np/terms-services/">https://mpg.com.np/terms-services/</a></p>
        <ul>
            <li>Finalized Content: We work on finalized content provided and do not offer graphic designing related content creation services, believing that ‘Content Creation’ and ‘Advertisement’ work best when separated. This is to provide you enough time and freedom to create, edit, and review your content as much as possible with your favorite Creation Partner before stepping into advertising strategy with us. However, we provide effective suggestions, written ad copies, and modifications when necessary.</li>
            <li>Invoice and Cost: After creating your ad campaign, you'll receive an invoice detailing the total cost and services provided.</li>
            <li>Payment Terms: For ad campaign activation, payment is required upfront. If you choose to pay post-approval by the Meta automation system, your campaign will be auto paused until payment is made. We don't offer partial payment arrangements or ask for advance payments.</li>
            <li>Non-Payment: If a campaign isn't paid for, our team may delete it from our records to ensure accurate and current data.</li>
            <li>Refunds: Should a campaign be discontinued for any reason; refunds will be proportional to the undelivered services. The refund method will be at our discretion.</li>
            <li>Disputes: Should there be any disputes or concerns regarding our services, we urge you to reach out directly. We're committed to resolving issues fairly and promptly.</li>
            <li>Amendments: MPG Solution can change these terms at any time. Any updates will be posted on our website, so do check periodically.</li>
        </ul>

        <h3>11. Why Choose MPG Solution?</h3>
        <ul>
            <li>Experience: With our roots stretching back to 2017 and offices in Nepal, Canada, and the USA, we bring international quality with a local touch.</li>
            <li>Expert Team: We're not just marketers; we're a team of storytellers, analysts, and tech enthusiasts dedicated to bringing your brand's story to the masses.</li>
            <li>Customer-Centric: Your business isn't just another project for us. We genuinely care about your growth and ensure our strategies align with your long-term visions.</li>
            <li>Transparent Operations: No hidden agendas, no concealed costs. We operate with full transparency, keeping you in the loop at every stage.</li>
        </ul>
        <!-- Contact and Signature Section -->
        <div class="contact-signature">
    <div class="contact-section">
        <h2>Contact Information</h2>
        <p>MPG Solution</p>
        <p>Pokhara-15, Nayagaun, Nepal</p>
        <p>Email: info@adsmpg.com</p>
        <p>Phone: +977 9856000601</p>
    </div>

    <div class="signature-section">
        <img src="<?php echo e(asset('uploads/tasbirs/Sign.png')); ?>" alt="Signature" class="sign">
        <p>Man P. Gurung<br><strong>Managing Director</strong></p>
        <p>MPG Solution Private Limited</p>
        <img src="<?php echo e(asset('uploads/tasbirs/Stamp.png')); ?>" alt="Stamp" class="stamp">
    </div>
</div>
    </div>
    
        <!-- Footer Section -->
        <div class="footer">
    <p>&copy; 2017-<?php echo e(\Carbon\Carbon::now()->year); ?> MPG Solution, All Rights Reserved.</p>
</div>

</body>
</html>
<?php /**PATH /home/mpgcomnp/app.mpg.com.np/resources/views/item/quotation_pdf.blade.php ENDPATH**/ ?>