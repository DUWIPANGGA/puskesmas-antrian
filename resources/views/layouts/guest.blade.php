<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>@yield('title', 'Auth') | puskesmas jagapura</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700;900&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet" />
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "error": "#e53e3e",
                        "outline": "#907898",
                        "outline-variant": "#dcc8e0",
                        "inverse-primary": "#f0a0cc",
                        "on-secondary-fixed-variant": "#4a3068",
                        "on-primary": "#ffffff",
                        "secondary-fixed": "#eedcff",
                        "surface-container-highest": "#ece2ec",
                        "on-tertiary-container": "#00334d",
                        "on-surface": "#2e1a28",
                        "on-secondary": "#ffffff",
                        "surface": "#fef7ff",
                        "primary": "#e040a0",
                        "on-error-container": "#9b1c1c",
                        "on-tertiary-fixed-variant": "#005580",
                        "tertiary": "#0096cc",
                        "tertiary-container": "#40c0ee",
                        "background": "#fef7ff",
                        "primary-fixed": "#ffd6ee",
                        "surface-dim": "#e0d6e0",
                        "on-primary-fixed-variant": "#a02070",
                        "on-background": "#2e1a28",
                        "surface-container-high": "#f2e8f2",
                        "on-error": "#ffffff",
                        "primary-fixed-dim": "#f0a0cc",
                        "on-secondary-container": "#2e2040",
                        "inverse-surface": "#2e1a28",
                        "error-container": "#ffe8e8",
                        "secondary-container": "#eedcff",
                        "on-surface-variant": "#604868",
                        "secondary-fixed-dim": "#c8a8e8",
                        "on-tertiary": "#ffffff",
                        "surface-tint": "#e040a0",
                        "surface-container-lowest": "#ffffff",
                        "inverse-on-surface": "#fef7ff",
                        "tertiary-fixed-dim": "#80d0f0",
                        "surface-variant": "#f2e8f2",
                        "on-tertiary-fixed": "#001a33",
                        "on-primary-container": "#2e1a28",
                        "on-primary-fixed": "#3d0028",
                        "tertiary-fixed": "#c8eaff",
                        "surface-container": "#f8eef8",
                        "on-secondary-fixed": "#1a1030",
                        "secondary": "#7c52aa",
                        "surface-bright": "#fef7ff",
                        "surface-container-low": "#fbf2fb",
                        "primary-container": "#f080c0"
                    },
                    fontFamily: {
                        "headline": ["DM Sans"],
                        "body": ["DM Sans"],
                        "label": ["DM Sans"]
                    },
                    borderRadius: {
                        "DEFAULT": "1rem",
                        "lg": "2rem",
                        "xl": "3rem",
                        "full": "9999px"
                    },
                },
            },
        }
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            vertical-align: middle;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        body {
            font-family: 'DM Sans', sans-serif;
        }
    </style>
    @stack('styles')
</head>

<body class="bg-background text-on-background min-h-screen flex items-center justify-center p-0 m-0 overflow-x-hidden">
    <main class="flex min-h-screen w-full flex-col lg:flex-row">
        <!-- Left Section: Branding & Message -->
        <section
            class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-primary to-secondary relative items-center justify-center p-12 overflow-hidden">
            <!-- Decorative Elements -->
            <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-[-10%] right-[-10%] w-96 h-96 bg-tertiary/20 rounded-full blur-3xl"></div>

            <div class="relative z-10 text-center flex flex-col items-center">
                <!-- Product Identity from JSON -->
                <a href="{{ url('/') }}"
                    class="mb-8 p-6 bg-white/10 backdrop-blur-md rounded-xl border border-white/20 shadow-2xl hover:scale-105 transition-transform duration-300 block decoration-transparent">
                    <h1 class="text-4xl lg:text-6xl font-black text-white tracking-tighter mb-2">puskesmas jagapura</h1>
                    <p class="text-white/80 text-xl font-medium tracking-wide">Patient Portal</p>
                </a>

                <div
                    class="relative w-full max-w-md aspect-square mb-8 rounded-xl overflow-hidden shadow-[0_20px_50px_rgba(0,0,0,0.2)]">
                    <img alt="Modern Medical Facility" class="object-cover w-full h-full"
                        src="https://lh3.googleusercontent.com/aida-public/AB6AXuAQ-qmggODt7H2Vg0c4JoMh10fj02_va9L57_Z-3tiRHxsEf3mMQH4OtR3aTPn27TnCkVReJruJidskJn8VXzayoqfSA3sRG_ewsBm8Zem3DxuabTXMakmlOLSHr0FX-tPnllLOXfoPIFlLlcRZjtOJB7hp6KLaDnbGG1OnYI3rW51FmrkE0cvdmXAWoFrsoarDbrtbWAYze9auucnBuzIxySPR1Bz1JZthyYGo_GwdK9zWCEaQztrBmJqV4FMKN63SuOBRuWys98B-" />
                    <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
                </div>

                <h2 class="text-3xl lg:text-4xl font-black text-white mb-4">Kesehatan Anda, Prioritas Kami.</h2>
                <p class="text-white/90 text-lg max-w-md font-medium">
                    Selamat Datang di puskesmas jagapura. Kami menyediakan layanan kesehatan terbaik dengan sentuhan
                    personal dan teknologi terkini.
                </p>
            </div>
        </section>

        <!-- Right Section: Login/Register Forms -->
        <section class="flex-1 flex items-center justify-center bg-surface p-6 md:p-12 relative">
            <div class="w-full max-w-md">
                <!-- Brand Mobile Header -->
                <div class="lg:hidden text-center mb-8">
                    <h1 class="text-3xl font-black text-primary tracking-tight">puskesmas jagapura</h1>
                    <p class="text-secondary font-bold">Kesehatan Anda, Prioritas Kami.</p>
                </div>

                @yield('header')

                @if ($errors->any())
                    <div
                        class="mb-5 px-4 py-3 bg-error/10 border border-error/20 rounded-xl text-sm text-error flex items-start gap-2">
                        <span class="material-symbols-outlined text-base mt-0.5">error</span>
                        <ul class="list-none space-y-0.5 m-0 p-0 text-left">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('status'))
                    <div
                        class="mb-5 px-4 py-3 bg-tertiary-fixed/40 border border-tertiary-fixed rounded-xl text-sm text-on-tertiary-container flex items-center gap-2">
                        <span class="material-symbols-outlined text-base">check_circle</span>
                        {{ session('status') }}
                    </div>
                @endif

                @if (session('success'))
                    <div
                        class="mb-5 px-4 py-3 bg-tertiary-fixed/40 border border-tertiary-fixed rounded-xl text-sm text-on-tertiary-container flex items-center gap-2">
                        <span class="material-symbols-outlined text-base">check_circle</span>
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Tabbed Form Container -->
                <div class="bg-white rounded-xl shadow-[0_8px_30px_rgba(224,64,160,0.1)] overflow-hidden">

                    @hasSection('tabs')
                        @yield('tabs')
                    @endif

                    <!-- Form Content -->
                    <div class="p-8">
                        @yield('content')
                    </div>
                </div>

                <!-- Footer Links -->
                <div class="mt-8 flex flex-col items-center gap-4 text-center">
                    @yield('footer_text')

                    <div class="flex gap-4 text-xs font-bold text-secondary">
                        <a class="hover:text-primary transition-colors" href="#">Privacy Policy</a>
                        <span class="text-outline-variant">•</span>
                        <a class="hover:text-primary transition-colors" href="#">Terms of Service</a>
                        <span class="text-outline-variant">•</span>
                        <a class="hover:text-primary transition-colors" href="#">Contact Support</a>
                    </div>
                    <p class="text-xs text-outline-variant font-medium mt-2">© {{ date('Y') }} puskesmas jagapura.
                        All rights reserved.</p>
                </div>
            </div>
        </section>
    </main>

    @stack('scripts')
</body>

</html>
