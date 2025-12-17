<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Portal Lab Teknik Kimia UAD</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #0d6efd;
            --secondary: #6c757d;
            --success: #198754;
            --danger: #dc3545;
            --light: #f8f9fa;
            --dark: #212529;
        }
        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: #333;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: white;
            padding: 15px 30px;
            border-radius: 0 0 15px 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 28px;
            font-weight: bold;
            color: var(--primary);
        }
        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 500;
        }
        .user-info span:first-child { font-size: 32px; }
        .card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin-bottom: 25px;
        }
        .lab-selector {
            text-align: center;
            padding: 40px 20px;
        }
        .lab-selector select {
            padding: 15px 20px;
            font-size: 18px;
            border-radius: 50px;
            border: none;
            background: var(--primary);
            color: white;
            width: 100%;
            max-width: 600px;
            box-shadow: 0 5px 15px rgba(13,110,253,0.3);
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin-top: 30px;
        }
        .menu-card {
            background: white;
            padding: 30px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: all 0.3s;
            text-decoration: none;
            color: #333;
        }
        .menu-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }
        .menu-card h3 {
            margin-top: 15px;
            font-size: 20px;
        }
        .menu-card span {
            font-size: 50px;
            display: block;
            margin-bottom: 10px;
        }
        .no-lab {
            text-align: center;
            padding: 80px 20px;
            color: white;
            font-size: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">Lab Kimia UAD</div>
        <div class="user-info">
            <span>User</span>
            <div>
                <strong>Rudi Hartono</strong><br>
                <small>211010XXX - Teknik Kimia</small>
            </div>
        </div>
    </div>

    <div class="container">
        @yield('content')
    </div>
</body>
</html>