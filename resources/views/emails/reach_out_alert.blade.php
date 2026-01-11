<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Received - Group Resilience</title>
    <style>
        /* Reset & Global Styles */
        /* Reset & Global Styles */
        body, html {
            margin: 0;
            padding: 0;
            background-color: #f4f4f7;
            font-family: 'Arial', sans-serif;
            color: #333333;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        /* Wrapper */
        .email-wrapper {
            width: 100%;
            padding: 20px 0;
            background-color: #f4f4f7;
        }

        /* Container */
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
        }

        /* Header */
        .header {
            background: linear-gradient(90deg, #020169, #020169);
            padding: 40px 20px;
            text-align: center;
            color: #ffffff;
        }

        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
            line-height: 1.2;
        }

        /* Content */
        .content {
            padding: 30px 25px;
            line-height: 1.6;
            color: #555555;
            text-align: left;
        }

        .content p {
            margin-bottom: 20px;
            font-size: 16px;
        }

        /* Notice Panel */
        .panel {
            background-color: #fff3cd;
            border-left: 6px solid #ffeeba;
            padding: 15px 20px;
            margin: 20px 0;
            border-radius: 8px;
            font-weight: 600;
            color: #856404;
            text-align: left;
            font-size: 15px;
        }

        /* Footer */
        .footer {
            background-color: #f4f4f7;
            padding: 25px 15px;
            text-align: center;
            font-size: 13px;
            color: #272525;
            word-break: break-word;   /* ensures text doesn’t overflow */
            line-height: 1.5;
        }

        .footer a {
            color: #ffffff;
            word-break: break-word;
        }

        /* Responsive */
        @media screen and (max-width: 640px) {
            .email-container {
                width: 100% !important;
                margin: auto !important;
            }

            .header h1 {
                font-size: 22px !important;
            }

            .content {
                padding: 20px 15px !important;
            }

            .content p {
                font-size: 18px !important;
            }

            .panel {
                font-size: 16px !important;
                padding: 15px !important;
            }

            .footer {
                font-size: 12px !important;
                padding: 20px 10px !important;
                text-align: left !important;
                display: block !important;
            }

            .footer p {
                font-size: 14px !important;
                margin: 8px 0 !important;
            }

            .footer a {
                display: block !important; /* stack links on mobile */
                margin-top: 6px !important;
            }
        }

    </style>
</head>
<body>
<div class="email-wrapper">
    <div class="email-container">

        {{-- Header --}}
        <div class="header" style="background: #020169;">
            <div style="text-align: center;">
                <img src="{{ asset('images/brand/GR-logo-blanc.png') }}" alt=""
                     style="padding: 10px; width: 60%; height: 120px; object-fit: contain;">
            </div>
        </div>

        {{-- Body --}}
        <div class="content" style="padding: 30px 55px;">
            <p>Dear {{ $name ?? '' }},</p>

            <p>Thank you for contacting Group Resilience.</p>

            <p>We have received your message, and one of our team members will review your request shortly. If a
                follow-up is necessary, expect to hear from us within the next <strong>1–2 business days</strong>.
            </p>

            <div class="panel">
                <p> ⚠️ Please note that this is an automated confirmation; this inbox is not monitored. For further
                    information please reach out to <a href="mailto:information@groupresilience.com">information@groupresilience.com</a>.
                    If your request is urgent, please contact our 24/7 Crisis Response Team at <a
                        href="mailto:urgent@groupresilience.com">urgent@groupresilience.com</a>.</p>
            </div>
        </div>

        {{-- Footer --}}
        <div class="footer" style="color: #ffffff!important; padding: 20px; background: #020169; box-sizing: initial !important;">
            <p>We appreciate your interest in Group Resilience. <br>
                Best regards,<br>
                <strong>The Group Resilience Team</strong><br>
                Group Resilience LTD, UK | Group Resilience SA, Switzerland<br>
                <a href="https://www.groupresilience.com" style="color: #fdfdfd !important;">www.groupresilience.com</a>
            </p>
        </div>

    </div>
</div>
</body>
</html>
