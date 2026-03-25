<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Payment Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #4CAF50;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f9f9f9;
            padding: 20px;
            border: 1px solid #ddd;
        }
        .footer {
            background-color: #f0f0f0;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-radius: 0 0 5px 5px;
        }
        .info-box {
            background-color: #fff;
            padding: 15px;
            margin: 15px 0;
            border-left: 4px solid #4CAF50;
        }
        .label {
            font-weight: bold;
            color: #4CAF50;
        }
        .value {
            margin-top: 5px;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>✓ Payment Successfully Recorded</h1>
        </div>

        <div class="content">
            <p>Dear <strong>{{ $student->fName }} {{ $student->lName }}</strong>,</p>

            <p>Your payment has been successfully recorded in our system. Below are the details of your payment:</p>

            <div class="info-box">
                <div>
                    <span class="label">Student Name:</span>
                    <div class="value">{{ $student->fName }} {{ $student->lName }}</div>
                </div>

                <div style="margin-top: 10px;">
                    <span class="label">Student ID:</span>
                    <div class="value">{{ $student->AutoID }}</div>
                </div>

                <div style="margin-top: 10px;">
                    <span class="label">Class:</span>
                    <div class="value">{{ $classRoom->cName }}</div>
                </div>

                <div style="margin-top: 10px;">
                    <span class="label">Class Fee:</span>
                    <div class="value">Rs. {{ number_format($classRoom->classfee ?? 0, 2) }}</div>
                </div>

                <div style="margin-top: 10px;">
                    <span class="label">Month:</span>
                    <div class="value">{{ $months[$month] }}</div>
                </div>

                <div style="margin-top: 10px;">
                    <span class="label">Payment Date:</span>
                    <div class="value">{{ now()->format('F j, Y \a\t g:i A') }}</div>
                </div>
            </div>

            <p>If you have any questions about this payment or need further assistance, please don't hesitate to contact us.</p>

            <p>Best regards,<br>
            <strong>CHROMA Management</strong></p>
        </div>

        <div class="footer">
            <p>This is an automated email. Please do not reply to this email.</p>
            <p>&copy; {{ date('Y') }} CHROMA. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
