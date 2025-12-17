{{-- resources/views/auth/registrasi-success.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Berhasil - RegLab UAD</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes checkmark {
            0% {
                stroke-dashoffset: 100;
            }
            100% {
                stroke-dashoffset: 0;
            }
        }
        
        .checkmark {
            stroke-dasharray: 100;
            stroke-dashoffset: 100;
            animation: checkmark 0.8s ease-in-out forwards;
            animation-delay: 0.3s;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-green-900 via-emerald-900 to-teal-900 min-h-screen flex items-center justify-center p-4">
    
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md p-8 text-center">
        <!-- Success Icon with Animation -->
        <div class="relative inline-block mb-6">
            <div class="bg-gradient-to-br from-green-400 to-emerald-500 rounded-full p-6">
                <svg class="w-20 h-20 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path class="checkmark" stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <!-- Sparkles -->
            <div class="absolute -top-2 -right-2 text-yellow-400 text-3xl animate-bounce">✨</div>
            <div class="absolute -bottom-2 -left-2 text-yellow-400 text-2xl animate-bounce" style="animation-delay: 0.2s;">✨</div>
        </div>

        <!-- Title -->
        <h1 class="text-3xl font-bold text-gray-800 mb-2">
            🎉 Registrasi Berhasil!
        </h1>
        <p class="text-gray-600 mb-8">
            Selamat datang di Sistem RegLab UAD
        </p>

        <!-- User Info Card -->
        <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-2xl p-6 mb-8 border-2 border-indigo-200">
            <p class="text-gray-600 text-sm mb-3">Akun Anda telah dibuat dengan detail:</p>
            
            <div class="space-y-3">
                <div class="bg-white rounded-xl p-4">
                    <p class="text-xs text-gray-500 mb-1">Nama Lengkap</p>
                    <p class="text-lg font-bold text-gray-800">{{ session('nama') }}</p>
                </div>
                
                <div class="bg-white rounded-xl p-4">
                    <p class="text-xs text-gray-500 mb-1">User ID Login</p>
                    <div class="flex items-center justify-center">
                        <code class="text-2xl font-bold text-indigo-600 bg-indigo-100 px-4 py-2 rounded-lg">
                            {{ session('userID') }}
                        </code>
                        <button 
                            onclick="copyUserID('{{ session('userID') }}')"
                            class="ml-2 text-indigo-600 hover:text-indigo-800"
                            title="Copy User ID"
                        >
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                        </button>
                    </div>
                    <p class="text-xs text-gray-500 mt-2" id="copyMessage"></p>
                </div>
            </div>
        </div>

        <!-- Important Info -->
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6 text-left">
            <div class="flex">
                <svg class="w-5 h-5 text-blue-600 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                <div class="text-xs text-blue-700">
                    <p class="font-semibold mb-2">Penting untuk diingat:</p>
                    <ul class="list-disc list-inside space-y-1">
                        <li>Simpan <strong>User ID</strong> Anda dengan baik</li>
                        <li>Gunakan User ID dan password untuk login</li>
                        <li>Role Anda: <strong>Mahasiswa</strong></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="space-y-3">
            <a 
                href="{{ route('login') }}"
                class="block w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold py-4 rounded-xl transition duration-300 transform hover:scale-[1.02] hover:shadow-xl"
            >
                🔐 Login Sekarang
            </a>
            
            <a 
                href="{{ route('home') }}"
                class="block w-full bg-white border-2 border-gray-300 text-gray-700 hover:bg-gray-50 font-semibold py-3 rounded-xl transition duration-300"
            >
                🏠 Kembali ke Beranda
            </a>
        </div>
    </div>

    <script>
        function copyUserID(userID) {
            navigator.clipboard.writeText(userID).then(function() {
                const message = document.getElementById('copyMessage');
                message.textContent = '✅ User ID berhasil dicopy!';
                message.classList.add('text-green-600', 'font-semibold');
                
                setTimeout(function() {
                    message.textContent = '';
                }, 3000);
            });
        }
    </script>

</body>
</html>