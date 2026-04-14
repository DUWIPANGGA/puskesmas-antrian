<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>CandyClinic ✨ | Joyful Future of Care</title>
    <!-- Tailwind + Google Fonts + Material Icons -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "inverse-primary": "#fe96c2",
                        "on-primary-container": "#6c1e46",
                        "on-primary-fixed-variant": "#77274f",
                        "on-error-container": "#68001f",
                        "primary-container": "#ffa7cb",
                        "background": "#fff8f8",
                        "on-secondary-fixed-variant": "#3e6c6b",
                        "surface-container-lowest": "#ffffff",
                        "secondary-fixed-dim": "#bbece9",
                        "on-error": "#fff7f7",
                        "tertiary-fixed": "#6bf2e6",
                        "surface": "#fff8f8",
                        "primary": "#974169",
                        "on-primary-fixed": "#510530",
                        "on-background": "#4f2438",
                        "on-surface-variant": "#835065",
                        "surface-container-highest": "#ffd9e5",
                        "outline": "#a26b81",
                        "on-secondary": "#e0fffd",
                        "surface-container": "#ffe8ee",
                        "error-dim": "#770326",
                        "surface-tint": "#974169",
                        "on-primary": "#fff7f8",
                        "outline-variant": "#dea1b8",
                        "primary-dim": "#88355d",
                        "primary-fixed": "#ffa7cb",
                        "error-container": "#f76a80",
                        "tertiary-fixed-dim": "#5ae3d8",
                        "surface-bright": "#fff8f8",
                        "tertiary-dim": "#005e58",
                        "on-tertiary-fixed-variant": "#00635d",
                        "secondary-container": "#c9faf8",
                        "on-secondary-fixed": "#204f4e",
                        "tertiary": "#006b65",
                        "on-tertiary-container": "#005853",
                        "secondary": "#396765",
                        "on-secondary-container": "#346260",
                        "surface-dim": "#ffcdde",
                        "on-surface": "#4f2438",
                        "inverse-surface": "#1c0711",
                        "secondary-dim": "#2c5a59",
                        "error": "#ac3149",
                        "tertiary-container": "#6bf2e6",
                        "inverse-on-surface": "#b893a0",
                        "on-tertiary": "#e1fffb",
                        "surface-variant": "#ffd9e5",
                        "surface-container-low": "#fff0f3",
                        "surface-container-high": "#ffe0ea",
                        "on-tertiary-fixed": "#004440",
                        "primary-fixed-dim": "#fb93bf",
                        "secondary-fixed": "#c9faf8"
                    },
                    fontFamily: {
                        "headline": ["DM Sans", "sans-serif"],
                        "body": ["DM Sans", "sans-serif"],
                        "label": ["DM Sans", "sans-serif"]
                    },
                    borderRadius: { "DEFAULT": "1rem", "lg": "2rem", "xl": "3rem", "full": "9999px" },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'glow-pulse': 'glowPulse 2.5s ease-in-out infinite',
                        'slide-up-fade': 'slideUpFade 0.5s cubic-bezier(0.2, 0.9, 0.4, 1.1) forwards',
                        'bounce-soft': 'bounceSoft 1s ease infinite',
                        'spin-slow': 'spin 8s linear infinite',
                        'shimmer': 'shimmer 2s infinite',
                        'marquee': 'marquee 20s linear infinite',
                    },
                    keyframes: {
                        float: { '0%, 100%': { transform: 'translateY(0px)' }, '50%': { transform: 'translateY(-12px)' } },
                        glowPulse: { '0%, 100%': { boxShadow: '0 0 5px rgba(151,65,105,0.3)' }, '50%': { boxShadow: '0 0 22px rgba(151,65,105,0.7)' } },
                        slideUpFade: { '0%': { opacity: '0', transform: 'translateY(20px)' }, '100%': { opacity: '1', transform: 'translateY(0)' } },
                        bounceSoft: { '0%,100%': { transform: 'translateY(0)' }, '50%': { transform: 'translateY(-6px)' } },
                        shimmer: { '0%': { backgroundPosition: '-200% 0' }, '100%': { backgroundPosition: '200% 0' } },
                        marquee: { '0%': { transform: 'translateX(0%)' }, '100%': { transform: 'translateX(-100%)' } }
                    }
                }
            },
        }
    </script>
    <style>
        body { font-family: 'DM Sans', sans-serif; scroll-behavior: smooth; overflow-x: hidden; }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; vertical-align: middle; display: inline-flex; align-items: center; justify-content: center; }
        .hover-lift { transition: transform 0.25s cubic-bezier(0.2, 0.9, 0.4, 1.1), box-shadow 0.3s ease; }
        .hover-lift:hover { transform: translateY(-6px) scale(1.02); box-shadow: 0 25px 35px -12px rgba(151,65,105,0.25); }
        .glass-nav { background: rgba(255, 248, 248, 0.75); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border-bottom: 1px solid rgba(224,64,160,0.15); }
        .bento-card { transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94); }
        .bento-card:hover { transform: translateY(-8px) scale(1.01); background: linear-gradient(135deg, #ffffff, #fff5f9); }
        .shimmer-text { background: linear-gradient(120deg, #974169, #f5a3c1, #974169); background-size: 200% auto; -webkit-background-clip: text; background-clip: text; color: transparent; animation: shimmer 3s linear infinite; }
        .rotate-hover { transition: transform 0.4s ease; }
        .rotate-hover:hover { transform: rotate(3deg) scale(1.02); }
        .pulse-ring { animation: glowPulse 2s infinite; }
        .floating-badge { animation: float 4s ease-in-out infinite; }
        .scroll-reveal { opacity: 0; transform: translateY(30px); transition: opacity 0.7s ease, transform 0.7s ease; }
        .scroll-reveal.revealed { opacity: 1; transform: translateY(0); }
        @keyframes gradientBG { 0% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } 100% { background-position: 0% 50%; } }
        .animated-bg-cta { background: linear-gradient(95deg, #e040a0, #974169, #7c52aa, #e040a0); background-size: 300% 300%; animation: gradientBG 8s ease infinite; }
    </style>
</head>
<body class="bg-background text-on-background selection:bg-primary-container selection:text-on-primary-container overflow-x-hidden">
    <!-- Glassmorphic Navbar + micro-interactions -->
    <header class="fixed top-0 w-full z-50 glass-nav transition-all duration-300">
        <nav class="flex justify-between items-center px-6 md:px-10 h-20 max-w-7xl mx-auto font-['DM_Sans'] font-bold">
            <div class="text-2xl font-black tracking-tight cursor-pointer group flex items-center gap-1">
                <span class="material-symbols-outlined text-primary text-3xl group-hover:scale-110 transition-transform duration-200">favorite</span>
                <span class="bg-gradient-to-r from-primary to-secondary bg-clip-text text-transparent">CandyClinic</span>
            </div>
            <div class="hidden md:flex items-center gap-8 text-base">
                <a class="text-on-surface/80 hover:text-primary transition-all duration-300 relative after:absolute after:bottom-[-6px] after:left-0 after:w-0 after:h-[2px] after:bg-primary after:transition-all after:duration-300 hover:after:w-full" href="#">Services</a>
                <a class="text-on-surface/80 hover:text-primary transition-all duration-300 relative after:absolute after:bottom-[-6px] after:left-0 after:w-0 after:h-[2px] after:bg-primary after:transition-all after:duration-300 hover:after:w-full" href="#">Specialists</a>
                <a class="text-on-surface/80 hover:text-primary transition-all duration-300 relative after:absolute after:bottom-[-6px] after:left-0 after:w-0 after:h-[2px] after:bg-primary after:transition-all after:duration-300 hover:after:w-full" href="#">About</a>
                <a class="text-on-surface/80 hover:text-primary transition-all duration-300 relative after:absolute after:bottom-[-6px] after:left-0 after:w-0 after:h-[2px] after:bg-primary after:transition-all after:duration-300 hover:after:w-full" href="#">Contact</a>
            </div>
            <a href="{{ route('login') }}" class="bg-primary text-on-primary px-6 py-2.5 rounded-full font-bold shadow-md hover:shadow-xl transition-all duration-300 active:scale-95 flex items-center gap-2 group decoration-transparent">
                <span>✨ Book Now</span>
                <span class="material-symbols-outlined text-lg group-hover:translate-x-1 transition">arrow_forward</span>
            </a>
        </nav>
    </header>

    <main class="pt-20">
        <!-- HERO: animated + interactive elements -->
        <section class="relative px-6 py-20 md:py-28 overflow-hidden">
            <div class="absolute top-20 left-10 w-72 h-72 bg-primary/20 rounded-full blur-[80px] -z-5 animate-pulse"></div>
            <div class="absolute bottom-20 right-10 w-80 h-80 bg-tertiary/20 rounded-full blur-[90px] -z-5 animate-pulse" style="animation-delay: 1s;"></div>
            <div class="max-w-7xl mx-auto grid md:grid-cols-2 gap-12 items-center">
                <div class="z-10 text-center md:text-left scroll-reveal">
                    <span class="inline-flex items-center gap-2 px-5 py-2 bg-secondary-container text-on-secondary-container rounded-full text-sm font-bold mb-6 tracking-wide backdrop-blur-sm border border-white/40 shadow-sm floating-badge">
                        <span class="material-symbols-outlined text-sm">sparkle</span> ✨ GEN-Z CARE, NEXT LEVEL
                    </span>
                    <h1 class="text-5xl md:text-7xl font-black leading-tight mb-6">
                        Excellence in <span class="text-primary italic inline-block hover:scale-105 transition-transform duration-300">Joyful</span> Care
                    </h1>
                    <p class="text-xl text-on-surface-variant mb-10 max-w-lg leading-relaxed">
                        Sanctuary Clinic combines world-class medical expertise with a vibrant, human-centered experience. We believe healing starts with a smile.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center md:justify-start">
                        <a href="{{ route('login') }}" class="bg-primary text-on-primary px-8 py-4 rounded-full text-lg font-bold shadow-[0_12px_24px_-8px_rgba(151,65,105,0.4)] hover:shadow-[0_20px_30px_-10px_rgba(151,65,105,0.5)] transition-all duration-300 hover:-translate-y-1 flex items-center gap-2 group decoration-transparent w-fit">
                            <span>Book Appointment</span>
                            <span class="material-symbols-outlined group-hover:translate-x-1 transition">calendar_month</span>
                        </a>
                        <button class="bg-white/70 backdrop-blur-sm border-2 border-primary/30 text-primary px-8 py-4 rounded-full text-lg font-bold hover:bg-primary/5 transition-all duration-300 hover:-translate-y-1 flex items-center gap-2">
                            <span class="material-symbols-outlined">theater_comedy</span> Virtual Tour
                        </button>
                    </div>
                </div>
                <div class="relative scroll-reveal" style="transition-delay: 0.1s;">
                    <div class="absolute -top-16 -left-16 w-72 h-72 bg-primary/15 rounded-full blur-3xl animate-spin-slow"></div>
                    <div class="relative rounded-2xl overflow-hidden shadow-2xl rotate-1 hover:rotate-0 transition-all duration-700 ease-out">
                        <img alt="Modern Medical Facility" class="w-full h-[500px] object-cover hover:scale-105 transition duration-700" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAZA4GgUcAzNLrheS1S7KN_-RbQ1S2FXwsvIdjZ4QedDJ39ZcsspUNyVrxv4-6e38PxXVEwIAuiAkx6SA_E6RcxQ-4vwt2FOhy_omAN6GPgMlNKubG_5lmP2oXrxIQU6s259TGl1UdQEcrOv6y-L6TCuKMyjLyw5dIfNb06rQKmmfdR1ELRZHgkI9wuJqqm0WlzbnX5DvZ3RUwh9O_9PdyFTAmufDMLoDYXEYdJJIe6nDrYCi25lB6iVv2itQOrt5OkUdotYXsi6MPp"/>
                    </div>
                    <div class="absolute -bottom-6 -left-6 bg-white/90 backdrop-blur-md p-5 rounded-2xl shadow-xl hover-lift flex items-center gap-4 border border-white/40">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-secondary-container to-secondary flex items-center justify-center text-on-secondary-container animate-pulse">
                            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star</span>
                        </div>
                        <div>
                            <p class="font-black text-on-surface text-xl">4.9<span class="text-sm">/5</span></p>
                            <p class="text-sm font-medium text-slate-500">2k+ happy reviews</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- BENTO GRID: animated hover & modern -->
        <section class="px-6 py-24 bg-surface-container-low">
            <div class="max-w-7xl mx-auto">
                <div class="mb-16 text-center scroll-reveal">
                    <h2 class="text-4xl md:text-5xl font-black mb-4 italic inline-flex items-center gap-2">Our Specialized Clinics <span class="material-symbols-outlined text-primary text-4xl">auto_awesome</span></h2>
                    <p class="text-on-surface-variant max-w-2xl mx-auto text-lg">Discover a new standard of healthcare across our specialized departments, designed for your comfort.</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <!-- General Medicine -->
                    <div class="md:col-span-2 bg-white p-6 rounded-2xl shadow-md hover:shadow-2xl transition-all duration-500 bento-card group border border-pink-100 scroll-reveal">
                        <div class="w-14 h-14 rounded-2xl bg-primary-container flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined text-on-primary-container text-3xl">stethoscope</span>
                        </div>
                        <h3 class="text-2xl font-bold text-on-surface mb-2">General Medicine</h3>
                        <p class="text-on-surface-variant mb-5">Comprehensive primary care for all ages with a focus on preventative wellness and long-term health management.</p>
                        <img alt="General Medicine Consultation" class="rounded-xl h-48 w-full object-cover transition-all duration-500 group-hover:scale-[1.02]" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCBVe1WiEb3HDUT3iHD3JG-Ug2Aawwjc5z8-t_F37CA09cYyAA84-YQf4nNJHApQh61Og1gTbhJ14n36e4_naonH_FfILfTigNTIUNvnJyAR7sD1i8jpRfoEklq1fnADk2490ZgWKIDm4Qwv0A8aAxyT7xXzk_ifa1uq4OEw421E0snzOW9dp5chhfb0ei6QzhoMu0vND3gn51K8PB6oK5EfgUKoHsYeJyf3BUDbsgCAYgxh74ZuDNjP4RM_Zf8eDj93R6xkaZL-tXi"/>
                    </div>
                    <!-- Pediatrics -->
                    <div class="bg-secondary-container p-6 rounded-2xl shadow-md hover:shadow-2xl transition-all duration-500 bento-card scroll-reveal">
                        <div class="w-14 h-14 rounded-2xl bg-white flex items-center justify-center mb-5 group-hover:rotate-3 transition">
                            <span class="material-symbols-outlined text-[#7c52aa] text-3xl">child_care</span>
                        </div>
                        <h3 class="text-2xl font-bold text-on-secondary-container mb-2">Pediatrics</h3>
                        <p class="text-on-secondary-container/80 mb-5">Creating a fun, fearless environment for our youngest patients to grow healthy and strong.</p>
                        <img alt="Pediatrics Care" class="rounded-xl h-32 w-full object-cover mt-auto transition-all" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCNx6fPbC3Ur4V3FRRRIfICmTQDJsPPZoMUqcKvkcmPqD7qBDHC3errE3tePAFPw04YV5TMg5TfmYQpCGEYFmYg-9DlewJYyoD8eJxtKlV-0_Xk8YM1ZpYXQyfHz6nK-QrNZjDr6p3AJ0VftFUsaVQgfCTN1lT2gVZlmBfXDZyd-N9MWw78w8Lxi_lbpcpYRD6T4EfvsSa3BHLVA_d9bks7tZfVtFfg0SXK0HcKzD62EpSP5T_OwvtKjSraVLPKSRRfJeUbDKjGFBLG"/>
                    </div>
                    <!-- Dental Care -->
                    <div class="bg-tertiary-container p-6 rounded-2xl shadow-md hover:shadow-2xl transition-all duration-500 bento-card scroll-reveal">
                        <div class="w-14 h-14 rounded-2xl bg-white flex items-center justify-center mb-5">
                            <span class="material-symbols-outlined text-[#0096cc] text-3xl">dentistry</span>
                        </div>
                        <h3 class="text-2xl font-bold text-on-tertiary-container mb-2">Dental Care</h3>
                        <p class="text-on-tertiary-container/80 mb-5">Modern dentistry that makes you smile. Painless, efficient, and aesthetic care.</p>
                        <img alt="Modern Dental Clinic" class="rounded-xl h-32 w-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCI8mZfN3RNK24dTdEJZEfK6VfVdiEhAYZoHxUUHj0LQ4Ub9nP_Yrb6mWy4f7azl7C3SiJ5NPyzHf0ba4RSNf2vfzapwdNwMOdPurJ2BQBIWKpCT6FtN-EPv-IFW5xm3k40PdOlyVHTP3EJ15YXV0iWBHFSvRck82Sbdh0djt7TnhzWBe2OATJ1eJn30SI98SGZ14YwRl4VUPGYJ9GqemlJoEHwQnD4hnxDlZ-NzMLuOaD2bMCPibcFmiQc0BC84HX__ZeVfu3yWAKD"/>
                    </div>
                    <!-- Cardiology Expanded -->
                    <div class="md:col-span-4 bg-white p-8 rounded-2xl shadow-lg border border-pink-100 hover:shadow-2xl transition-all duration-500 flex flex-col md:flex-row items-center gap-8 scroll-reveal">
                        <div class="flex-1">
                            <div class="w-14 h-14 rounded-2xl bg-primary-container flex items-center justify-center mb-5 animate-pulse">
                                <span class="material-symbols-outlined text-on-primary-container text-3xl" style="font-variation-settings: 'FILL' 1;">favorite</span>
                            </div>
                            <h3 class="text-2xl font-bold text-on-surface mb-2">Cardiology Center</h3>
                            <p class="text-on-surface-variant text-lg mb-6">Leading-edge heart health diagnostic and treatment using the latest non-invasive technologies.</p>
                            <div class="grid grid-cols-2 gap-4 w-full">
                                <div class="bg-surface-container p-4 rounded-xl text-center hover:scale-105 transition-all">
                                    <p class="text-3xl font-black text-primary">24/7</p>
                                    <p class="text-sm font-bold text-on-surface-variant">Emergency Care</p>
                                </div>
                                <div class="bg-surface-container p-4 rounded-xl text-center hover:scale-105 transition-all">
                                    <p class="text-3xl font-black text-secondary">15+</p>
                                    <p class="text-sm font-bold text-on-surface-variant">Top Specialists</p>
                                </div>
                            </div>
                        </div>
                        <div class="flex-1 w-full">
                            <img alt="Cardiology Diagnostic" class="rounded-xl h-64 w-full object-cover rotate-hover transition-all" src="https://lh3.googleusercontent.com/aida-public/AB6AXuD5dke3Bao6s31-P0FAASGPy98lib7iXGNe1mccFxTchCtucjctVug06rKQmFnNMv1G_q7fxBZps65PrdTxBV0Rs4MwUHBg9GtwwDLRMxG10ioXDID_-mlsBEVV-wA9Bvr5rAUo6PYRMB3K5sxP9eSnyT5IouN32bb1y-n4LH22I8zcyD7dGUsxmtPKKSaAJX0Ff-xnoEOxYei70edQAwsMhAoR57hLzs2QCQbiy39nQnCF7nwFBxtlcGt3hpMlzXE1OYKF2iLp6fKy"/>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Why Sanctuary Section + interactive counters -->
        <section class="px-6 py-24 overflow-hidden">
            <div class="max-w-7xl mx-auto flex flex-col md:flex-row gap-16 items-center">
                <div class="flex-1 relative scroll-reveal">
                    <div class="absolute inset-0 bg-gradient-to-tr from-primary/10 to-secondary/10 rounded-full blur-2xl animate-spin-slow"></div>
                    <img alt="Patient Friendly Environment" class="relative rounded-2xl shadow-2xl z-10 w-full object-cover aspect-square hover:scale-[1.02] transition duration-700" src="https://lh3.googleusercontent.com/aida-public/AB6AXuB6Oct4tOO1b7-o7OsWJqxB8-HfB9aWIBzjja-KfAfXd8G6AY5TAJaSUO6a6-26lK_8QWhgIrYSmeN5v2uyqs2kSTaXpACWxQvuETJGvsJLmO-wWyQvmDaiMaq4xzFs6Bj5JDtD_eiDwVQRLQO65pnw0wz5SlacTct0t5xmnZpkHGokADAVezB0_IBXoLg4IwS57dH8Uwxj_aIRCjCxcndNsbbfq1OJOPZtThcr6W0asjAfXSFUeibyix0QC-7nq-4DJ41wyMnylFA4"/>
                </div>
                <div class="flex-1 scroll-reveal">
                    <h2 class="text-4xl font-black mb-12">Why Choose <span class="text-secondary italic relative inline-block after:content-[''] after:absolute after:w-full after:h-2 after:bg-secondary/30 after:bottom-0 after:left-0 after:rounded-full">Sanctuary?</span></h2>
                    <div class="space-y-8">
                        <div class="flex gap-6 items-start group hover:translate-x-2 transition-transform duration-300">
                            <div class="shrink-0 w-16 h-16 rounded-full bg-primary/10 flex items-center justify-center text-primary shadow-md group-hover:bg-primary group-hover:text-white transition-all"><span class="material-symbols-outlined text-2xl">app_registration</span></div>
                            <div><h4 class="text-xl font-bold mb-2">Online Registration</h4><p class="text-on-surface-variant">Skip the paperwork. Register in under 60 seconds through our intuitive mobile app or website.</p></div>
                        </div>
                        <div class="flex gap-6 items-start group hover:translate-x-2 transition-transform duration-300">
                            <div class="shrink-0 w-16 h-16 rounded-full bg-secondary/10 flex items-center justify-center text-secondary group-hover:bg-secondary group-hover:text-white transition-all"><span class="material-symbols-outlined text-2xl">update</span></div>
                            <div><h4 class="text-xl font-bold mb-2">Real-time Queue Tracking</h4><p class="text-on-surface-variant">Don't sit in a waiting room. Track your appointment status live and arrive just when it's your turn.</p></div>
                        </div>
                        <div class="flex gap-6 items-start group hover:translate-x-2 transition-transform duration-300">
                            <div class="shrink-0 w-16 h-16 rounded-full bg-tertiary/10 flex items-center justify-center text-tertiary group-hover:bg-tertiary group-hover:text-white transition-all"><span class="material-symbols-outlined text-2xl">medication</span></div>
                            <div><h4 class="text-xl font-bold mb-2">Integrated Pharmacy</h4><p class="text-on-surface-variant">Prescriptions are sent instantly to our in-house smart pharmacy for zero-wait pickup or home delivery.</p></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section with gradient + animated -->
        <section class="px-6 py-20">
            <div class="max-w-7xl mx-auto animated-bg-cta rounded-3xl p-12 md:p-20 relative overflow-hidden text-center text-white shadow-2xl transition-all hover:shadow-primary/30">
                <div class="absolute top-0 right-0 w-64 h-64 bg-white/15 rounded-full -translate-y-1/2 translate-x-1/2 blur-3xl animate-pulse"></div>
                <div class="absolute bottom-0 left-0 w-64 h-64 bg-black/10 rounded-full translate-y-1/2 -translate-x-1/2 blur-3xl"></div>
                <h2 class="text-4xl md:text-5xl font-black mb-6 relative z-10 flex flex-wrap justify-center gap-3">Ready for a Better Health Experience? <span class="material-symbols-outlined text-5xl">rocket_launch</span></h2>
                <p class="text-xl text-white/90 mb-10 max-w-2xl mx-auto relative z-10">Join thousands of happy patients who have discovered that healthcare can be joyful, efficient, and human.</p>
                <div class="relative z-10">
                    <a href="{{ route('login') }}" class="bg-white text-primary px-12 py-5 rounded-full text-xl font-bold shadow-2xl hover:shadow-white/30 transition-all duration-300 hover:scale-105 flex items-center gap-3 w-fit mx-auto decoration-transparent">
                        <span>✨ Book Your First Visit ✨</span>
                    </a>
                </div>
                <div class="mt-10 flex gap-6 justify-center text-sm font-medium tracking-wide">
                    <span class="flex items-center gap-1">⭐ 4.9 TrustScore</span>
                    <span class="flex items-center gap-1">🎉 2000+ reviews</span>
                    <span class="flex items-center gap-1">⚡ 98% satisfaction</span>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer with micro-interactions -->
    <footer class="w-full bg-white rounded-t-[48px] mt-12 border-t border-slate-100 shadow-[0_-20px_40px_-12px_rgba(124,82,170,0.08)]">
        <div class="max-w-7xl mx-auto p-10 flex flex-col md:flex-row justify-between items-center gap-8 font-['DM_Sans'] text-sm text-slate-500">
            <div class="flex flex-col items-center md:items-start gap-2">
                <div class="text-xl font-black bg-gradient-to-r from-primary to-secondary bg-clip-text text-transparent flex items-center gap-1">CandyClinic <span class="material-symbols-outlined text-primary">favorite</span></div>
                <p class="max-w-xs text-center md:text-left">© 2024 CandyClinic. Excellence in Joyful Care.</p>
            </div>
            <div class="flex gap-8 flex-wrap justify-center">
                <a class="hover:text-primary transition-all hover:-translate-y-0.5 duration-200" href="#">Privacy Policy</a>
                <a class="hover:text-primary transition-all hover:-translate-y-0.5" href="#">Terms of Service</a>
                <a class="text-primary font-semibold hover:underline decoration-wavy" href="#">Patient Portal</a>
                <a class="hover:text-primary transition-all hover:-translate-y-0.5" href="#">Careers</a>
            </div>
            <div class="flex gap-4">
                <div class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center text-slate-500 hover:bg-primary-container hover:text-on-primary-container transition-all cursor-pointer shadow-sm hover:shadow-md"><span class="material-symbols-outlined text-base">share</span></div>
                <div class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center text-slate-500 hover:bg-secondary-container hover:text-on-secondary-container transition-all"><span class="material-symbols-outlined text-base">public</span></div>
            </div>
        </div>
    </footer>

    <!-- scroll reveal script -->
    <script>
        const revealElements = document.querySelectorAll('.scroll-reveal');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('revealed');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1, rootMargin: "0px 0px -40px 0px" });
        revealElements.forEach(el => observer.observe(el));
        // additional floating effect for buttons
        document.querySelectorAll('button').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                alert("✨ Thanks for exploring CandyClinic! ✨\n(Booking demo — modern care is on the way)");
            });
        });
    </script>
</body>
</html>