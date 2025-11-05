<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cure - Verification Code</title>
    <style>
        body{
            background: #f3f7fc;
            font-family: Arial, sans-serif;
            padding: 40px 0;
            text-align: center;
        }
        .container{
            max-width: 460px;
            margin: auto;
            background: white;
            border-radius: 12px;
            padding: 40px 30px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.1);
        }
        .logo{
            width: 60px;
            margin-bottom: 10px;
        }
        h1{
            font-size: 22px;
            font-weight: 700;
            color: #1C2A4B;
            margin-bottom: 20px;
        }
        p{
            font-size: 14px;
            color: #555;
            margin-bottom: 25px;
        }
        .code-box{
            display: flex;
            justify-content: center;
            gap: 12px;
        }
        .code{
            width: 48px;
            height: 48px;
            background: #edf3ff;
            border-radius: 8px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 22px;
            font-weight: bold;
            color: #1d4ed8;
            border: 1px solid #d3defa;
        }
        .footer{
            margin-top: 25px;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>

<div class="container">

    <!-- الأيقونة -->
    <img src="{{ asset('images/cure-logo.png') }}" alt="Cure Logo" class="logo">

    <!-- اسم البرنامج -->
    <h1>Cure - Code Verification</h1>

    <p>Use the code below to verify your account.</p>

    <div class="code-box">
        @foreach(str_split($otp) as $digit)
            <div class="code">{{ $digit }}</div>
        @endforeach
    </div>

    <div class="footer">
        If you didn’t request this code, you can ignore this email.
    </div>

</div>

</body>
</html>
