<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Kepala Laboratorium</title>

    @vite('resources/css/app.css')
    @vite('resources/js/app.js')

    <style>
        body {
            font-size: 14px;
            color: #111827;
        }

        .welcome-box {
            max-width: 720px;
            margin: 3rem auto;
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 2rem;
        }

        .welcome-title {
            font-size: 1.4rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
        }

        .welcome-text {
            color: #4b5563;
            line-height: 1.7;
            margin-bottom: 1.5rem;
        }

        .welcome-actions {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .welcome-actions a {
            padding: 0.5rem 1rem;
            font-size: 0.85rem;
            border-radius: 6px;
            border: 1px solid #e5e7eb;
            background: #fff;
            color: #374151;
            text-decoration: none;
        }

        .welcome-actions a.primary {
            background: #2563eb;
            color: #fff;
            border-color: #2563eb;
        }

        .welcome-actions a.primary:hover {
            background: #1d4ed8;
        }

        .welcome-actions a:hover {
            background: #f9fafb;
        }
    </style>
</head>

<body class="h-full">

<div class="min-h-full">
    <x-kepala-lab.navbar :labs="$labs" :user="$user" />
    <x-kepala-lab.header>Dashboard</x-kepala-lab.header>

    <main class="px-4">
        <div class="welcome-box">
            <div class="welcome-title">
                Selamat Datang, {{ $user->Nama }}
            </div>

            <div class="welcome-text">
                Anda berada di sistem <strong>Manajemen Risk Assessment Laboratorium</strong>.
                <br><br>
                Melalui sistem ini, Anda dapat melakukan <strong>peninjauan</strong>,
                <strong>persetujuan</strong>, serta <strong>pemantauan</strong> Risk Assessment
                yang diajukan oleh laboran atau pengguna laboratorium.
            </div>

            <div class="welcome-actions">
                <a href="{{ route('kepala-lab.risk-assessment.index') }}" class="primary">
                    Review Risk Assessment
                </a>
                <a href="{{ route('kepala-lab.risk-assessment.report') }}">
                    Lihat Laporan
                </a>
            </div>
        </div>
    </main>
</div>
<script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script>

</body>
</html>
