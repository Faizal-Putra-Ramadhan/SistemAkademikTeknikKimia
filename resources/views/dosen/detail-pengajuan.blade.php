<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    @vite('resources/js/app.js')
    @vite('resources/css/app.css')
  
</head>

<body>

  <div class="min-h-full">

    <x-dosen.navbar :labs="$labs" :user="$user" />

    <x-dosen.header>Kelola Pengajuan Penelitian</x-dosen.header>

  </div>
  

  {{-- Pindahkan ke paling bawah, SETELAH konten --}}
  {{-- Pindahkan semua skrip ke paling bawah, SEBELUM </body> --}}
  
  <script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script>

</body>
</html>
