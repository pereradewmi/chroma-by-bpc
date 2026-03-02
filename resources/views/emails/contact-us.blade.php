<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Contact Form Submission</title>
</head>
<body>
    <h2>New Contact Form Submission</h2>

    <p><strong>Name:</strong> {{ $contactData['name'] }}</p>
    <p><strong>Email:</strong> {{ $contactData['email'] }}</p>
    <p><strong>Subject:</strong> {{ $contactData['subject'] }}</p>

    <p><strong>Message:</strong></p>
    <p>{!! nl2br(e($contactData['message'])) !!}</p>
</body>
</html>
