<?php
// index.php - useLOVELY E-Commerce Landing Page
require_once __DIR__ . '/config/env.php';
require_once __DIR__ . '/config/database.php';

try {
    $pdo = get_db_connection();
    $stmt = $pdo->query("SELECT * FROM site_config WHERE config_key IN ('promo_single_price', 'promo_combo_price')");
    $rows = $stmt->fetchAll();
    $config = [];
    foreach ($rows as $r) {
        $config[$r['config_key']] = $r['config_value'];
    }
} catch (Exception $e) {
    $config = [];
}

$promoSinglePrice = (float)($config['promo_single_price'] ?? 49.90);
$promoComboPrice = (float)($config['promo_combo_price'] ?? 99.99);

$formatPrice = function($price) {
    return number_format($price, 2, ',', '.');
};
?>
<!DOCTYPE html>
<html lang="pt-BR" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>use LOVELY | Coleção Exclusiva de Body Splashes</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Google Fonts: Cormorant Garamond for luxury serif headlines & Plus Jakarta Sans for body -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Lucide Icons CDN -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Mercado Pago SDK v2 -->
    <script src="https://sdk.mercadopago.com/js/v2"></script>
    
    <!-- Custom Tailwind Configuration -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        serif: ['"Cormorant Garamond"', 'Georgia', 'serif'],
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            rose: '#E8A5B8',
                            purple: '#9B72AA',
                            gold: '#E3A857',
                            mint: '#6FB3B0',
                            navy: '#3B4861',
                            dark: '#2A1F2D',
                            cream: '#FAF6F0',
                            softBg: '#FCF8F5'
                        }
                    },
                    boxShadow: {
                        'glow-rose': '0 10px 30px -5px rgba(232, 165, 184, 0.4)',
                        'glow-purple': '0 10px 30px -5px rgba(155, 114, 170, 0.4)',
                        'glow-gold': '0 10px 30px -5px rgba(227, 168, 87, 0.4)',
                        'glow-mint': '0 10px 30px -5px rgba(111, 179, 176, 0.4)',
                        'glow-navy': '0 10px 30px -5px rgba(59, 72, 97, 0.4)',
                    }
                }
            }
        }
    </script>

    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-10px) rotate(2deg); }
        }
        @keyframes pulseGlow {
            0%, 100% { opacity: 0.6; transform: scale(1); }
            50% { opacity: 0.9; transform: scale(1.05); }
        }
        .animate-float {
            animation: float 5s ease-in-out infinite;
        }
        .animate-pulse-glow {
            animation: pulseGlow 4s ease-in-out infinite;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.6);
        }
        .glass-nav {
            background: rgba(252, 248, 245, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
        }
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #FAF6F0;
        }
        ::-webkit-scrollbar-thumb {
            background: #D9AAB7;
            border-radius: 4px;
        }
    </style>
</head>
<body class="bg-brand-softBg text-neutral-800 font-sans antialiased selection:bg-brand-rose selection:text-white">

    <!-- Top Announcement Bar -->
    <div class="bg-gradient-to-r from-[#e7b8c8] via-[#a888b5] to-[#7dbfb8] text-white text-xs md:text-sm font-medium py-2 px-4 text-center tracking-wide shadow-sm flex items-center justify-center gap-2">
        <i data-lucide="sparkles" class="w-4 h-4 animate-spin-slow"></i>
        <span><strong>Oferta Especial:</strong> 1 Body Splash por <strong>R$ <?= $formatPrice($promoSinglePrice) ?></strong> ou leve <strong>3 por R$ <?= $formatPrice($promoComboPrice) ?></strong> • Entrega via Correios (PAC e SEDEX)</span>
        <i data-lucide="sparkles" class="w-4 h-4"></i>
    </div>

    <!-- Main Navigation Bar -->
    <header class="sticky top-0 z-40 glass-nav border-b border-rose-100/60 transition-all duration-300" id="mainHeader">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            
            <!-- Mobile Menu Toggle -->
            <button id="mobileMenuBtn" class="lg:hidden p-2 text-neutral-700 hover:text-brand-purple rounded-lg focus:outline-none" aria-label="Abrir menu">
                <i data-lucide="menu" class="w-6 h-6"></i>
            </button>

            <!-- Brand Logo -->
            <a href="#" class="flex items-center gap-3 group">
                <div class="w-10 h-10 rounded-full flex items-center justify-center transition-transform group-hover:scale-105">
                    <svg viewBox="0 0 100 100" class="w-10 h-10 drop-shadow-sm">
                        <defs>
                            <linearGradient id="petalPink" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" stop-color="#E8A5B8"/>
                                <stop offset="100%" stop-color="#D87093"/>
                            </linearGradient>
                            <linearGradient id="petalPurple" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" stop-color="#C3A5E8"/>
                                <stop offset="100%" stop-color="#8A51C7"/>
                            </linearGradient>
                            <linearGradient id="petalGold" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" stop-color="#FCD34D"/>
                                <stop offset="100%" stop-color="#F59E0B"/>
                            </linearGradient>
                        </defs>
                        <path d="M50,15 C45,35 25,45 15,55 C30,60 45,55 50,85 C55,55 70,60 85,55 C75,45 55,35 50,15 Z" fill="url(#petalPink)" opacity="0.8"/>
                        <path d="M50,20 C35,38 18,32 10,48 C25,52 38,58 50,80 C62,58 75,52 90,48 C82,32 65,38 50,20 Z" fill="url(#petalPurple)" opacity="0.6"/>
                        <path d="M50,28 C42,42 28,48 20,62 C32,64 42,68 50,82 C58,68 68,64 80,62 C72,48 58,42 50,28 Z" fill="url(#petalGold)" opacity="0.7"/>
                        <ellipse cx="50" cy="62" rx="4" ry="7" fill="#FFF" opacity="0.9"/>
                    </svg>
                </div>
                <div class="flex flex-col">
                    <span class="font-serif text-2xl font-semibold tracking-wider text-neutral-800 leading-none">
                        use<span class="font-bold text-neutral-900 uppercase tracking-widest text-xl ml-1">LOVELY</span>
                    </span>
                    <span class="text-[9px] uppercase tracking-[0.25em] text-neutral-500 font-medium">Body Splash Collection</span>
                </div>
            </a>

            <!-- Desktop Navigation Links -->
            <nav class="hidden lg:flex items-center gap-8">
                <a href="#fragrances" class="text-sm font-medium text-neutral-700 hover:text-brand-purple transition-colors">Coleção de Aromas</a>
                <a href="#quiz" class="text-sm font-medium text-neutral-700 hover:text-brand-purple transition-colors flex items-center gap-1.5">
                    <i data-lucide="wand2" class="w-4 h-4 text-brand-rose"></i>
                    <span>Perfume Finder</span>
                </a>
                <a href="#combo" class="text-sm font-medium text-neutral-700 hover:text-brand-purple transition-colors font-bold text-rose-600">Monte Seu Kit (R$ <?= $formatPrice($promoComboPrice) ?>)</a>
                <a href="#diferenciais" class="text-sm font-medium text-neutral-700 hover:text-brand-purple transition-colors">Diferenciais</a>
                <a href="#avaliacoes" class="text-sm font-medium text-neutral-700 hover:text-brand-purple transition-colors">Avaliações</a>
            </nav>

            <!-- Customer Account & Cart Actions -->
            <div class="flex items-center gap-3">
                <div id="customerAuthContainer">
                    <button onclick="openAuthModal('login')" class="flex items-center gap-2 px-3.5 py-2 rounded-full bg-white hover:bg-neutral-100 text-neutral-700 text-xs font-semibold transition-all border border-neutral-200 shadow-xs">
                        <i data-lucide="user" class="w-4 h-4 text-rose-600"></i>
                        <span class="hidden sm:inline">Entrar / Criar Conta</span>
                    </button>
                </div>

                <button id="cartBtn" onclick="openCart()" class="relative p-2.5 text-neutral-800 hover:text-brand-purple rounded-full hover:bg-white/80 transition-all shadow-xs border border-neutral-200/80">
                    <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                    <span id="cartCountBadge" class="absolute -top-1 -right-1 bg-brand-rose text-white text-[11px] font-bold w-5 h-5 rounded-full flex items-center justify-center shadow-xs">0</span>
                </button>
            </div>
        </div>

        <!-- Mobile Navigation Menu Dropdown -->
        <div id="mobileMenu" class="hidden lg:hidden bg-white/95 backdrop-blur-md border-b border-rose-100 px-6 py-6 transition-all">
            <nav class="flex flex-col gap-4">
                <a href="#fragrances" onclick="closeMobileMenu()" class="text-neutral-800 font-medium py-2 border-b border-neutral-100">Coleção de Aromas</a>
                <a href="#quiz" onclick="closeMobileMenu()" class="text-neutral-800 font-medium py-2 border-b border-neutral-100 flex items-center justify-between">
                    <span>Perfume Finder (Quiz)</span>
                    <span class="bg-rose-100 text-rose-700 text-xs px-2 py-0.5 rounded-full">Novo</span>
                </a>
                <a href="#combo" onclick="closeMobileMenu()" class="text-neutral-800 font-medium py-2 border-b border-neutral-100">Monte Seu Kit (R$ <?= $formatPrice($promoComboPrice) ?>)</a>
                <a href="#diferenciais" onclick="closeMobileMenu()" class="text-neutral-800 font-medium py-2 border-b border-neutral-100">Diferenciais & Fixação</a>
                <a href="#avaliacoes" onclick="closeMobileMenu()" class="text-neutral-800 font-medium py-2">Avaliações das Clientes</a>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section id="hero" class="relative overflow-hidden py-16 lg:py-24 bg-gradient-to-b from-brand-softBg via-rose-50/40 to-brand-softBg transition-colors duration-500">
        <div id="heroBlob1" class="absolute top-1/4 left-10 w-72 h-72 bg-pink-200/40 rounded-full blur-3xl pointer-events-none animate-pulse-glow transition-colors duration-500"></div>
        <div id="heroBlob2" class="absolute top-1/3 right-10 w-80 h-80 bg-purple-200/30 rounded-full blur-3xl pointer-events-none animate-pulse-glow transition-colors duration-500" style="animation-delay: 2s"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                
                <div class="lg:col-span-6 text-center lg:text-left space-y-6">
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/80 border border-rose-200/80 shadow-xs text-xs md:text-sm font-semibold text-neutral-700 backdrop-blur-sm">
                        <span id="heroPing" class="w-2 h-2 rounded-full bg-rose-400 animate-ping"></span>
                        <span id="heroTagline" class="font-serif italic text-base text-rose-600">Floral Gourmand & Elegante</span>
                        <span class="text-neutral-300">•</span>
                        <span>Longa Fixação 236 mL</span>
                    </div>

                    <h1 id="heroTitle" class="font-serif text-4xl sm:text-5xl lg:text-6xl font-normal text-neutral-900 leading-[1.15] tracking-tight">
                        Velvet Bloom
                    </h1>

                    <div id="heroRefBadge" class="inline-block px-3.5 py-1.5 rounded-full bg-rose-100/80 text-rose-700 text-xs font-bold border border-rose-200">
                        ✨ Referência Olfativa: La Vie Est Belle (Lancôme)
                    </div>

                    <p id="heroDescription" class="text-neutral-600 text-base sm:text-lg max-w-xl mx-auto lg:mx-0 leading-relaxed font-light">
                        Floral gourmand, feminino, doce e elegante. Com notas de íris, flores brancas, baunilha, praliné e fundo envolvente.
                    </p>

                    <div class="flex items-baseline justify-center lg:justify-start gap-3">
                        <span class="text-xs uppercase font-medium text-neutral-400">Preço Especial:</span>
                        <span id="heroPrice" class="font-serif text-3xl font-bold text-neutral-900">R$ <?= $formatPrice($promoSinglePrice) ?></span>
                        <span class="text-xs text-rose-600 font-bold bg-rose-50 px-2.5 py-1 rounded-full border border-rose-100">Leve 3 por R$ <?= $formatPrice($promoComboPrice) ?></span>
                    </div>

                    <div class="pt-2 flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4">
                        <button id="heroBuyBtn" onclick="addToCart('velvet-bloom')" class="w-full sm:w-auto inline-flex items-center justify-center gap-3 bg-neutral-900 text-white px-8 py-4 rounded-full font-medium text-sm hover:bg-neutral-800 transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                            <i data-lucide="shopping-bag" class="w-4 h-4"></i>
                            <span>Adicionar por R$ <?= $formatPrice($promoSinglePrice) ?></span>
                        </button>

                        <a href="#combo" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-white/90 text-neutral-800 border border-neutral-300/80 px-7 py-4 rounded-full font-medium text-sm hover:bg-white hover:border-neutral-400 transition-all shadow-xs">
                            <i data-lucide="sparkles" class="w-4 h-4 text-amber-500"></i>
                            <span>Montar Trio por R$ <?= $formatPrice($promoComboPrice) ?></span>
                        </a>
                    </div>

                    <div class="pt-8 grid grid-cols-3 gap-4 border-t border-rose-100/80 text-center lg:text-left">
                        <div>
                            <p class="font-serif text-xl sm:text-2xl font-bold text-neutral-900">100%</p>
                            <p class="text-xs text-neutral-500 font-medium">Vegano & Cruelty-Free</p>
                        </div>
                        <div>
                            <p class="font-serif text-xl sm:text-2xl font-bold text-neutral-900">12 Horas</p>
                            <p class="text-xs text-neutral-500 font-medium">Fixação Suave & Marcante</p>
                        </div>
                        <div>
                            <p class="font-serif text-xl sm:text-2xl font-bold text-neutral-900">4.9 ★</p>
                            <p class="text-xs text-neutral-500 font-medium">+2.400 Avaliações Positivas</p>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-6 relative flex items-center justify-center">
                    <div id="heroImageBox" class="relative w-full max-w-md lg:max-w-none h-[440px] sm:h-[480px] flex flex-col items-center justify-between p-6 rounded-3xl bg-gradient-to-tr from-pink-100/60 via-rose-50/50 to-purple-50/60 backdrop-blur-md border border-white/80 shadow-2xl overflow-hidden transition-all duration-500">
                        
                        <div class="w-full flex items-center justify-between z-10">
                            <span class="text-xs font-semibold uppercase tracking-wider text-neutral-500">Aromas Signature</span>
                            <span class="text-xs bg-white/90 px-3 py-1 rounded-full text-neutral-700 shadow-2xs font-medium">236 mL / 8 fl oz</span>
                        </div>

                        <div class="relative z-10 w-full h-[280px] flex items-center justify-center my-auto transition-all duration-500">
                            <img id="heroImage" src="assets/images/velvet_bloom.jpg" alt="Velvet Bloom" class="max-h-full max-w-full object-contain mix-blend-multiply drop-shadow-2xl transition-all duration-500 animate-float">
                        </div>

                        <div class="z-10 flex items-center justify-center gap-2 sm:gap-3 bg-white/80 p-2 rounded-2xl backdrop-blur-md border border-white/80 shadow-sm w-full max-w-sm">
                            <button onclick="switchHeroScent(0)" class="hero-thumb p-1.5 rounded-xl transition-all border-2 border-rose-500 bg-rose-50 scale-105" title="Velvet Bloom">
                                <img src="assets/images/velvet_bloom.jpg" class="w-8 h-8 object-contain mix-blend-multiply">
                            </button>
                            <button onclick="switchHeroScent(1)" class="hero-thumb p-1.5 rounded-xl transition-all border-2 border-transparent hover:border-purple-300" title="Purple Kiss">
                                <img src="assets/images/purple_kiss.jpg" class="w-8 h-8 object-contain mix-blend-multiply">
                            </button>
                            <button onclick="switchHeroScent(2)" class="hero-thumb p-1.5 rounded-xl transition-all border-2 border-transparent hover:border-amber-300" title="Golden Glow">
                                <img src="assets/images/golden_glow.jpg" class="w-8 h-8 object-contain mix-blend-multiply">
                            </button>
                            <button onclick="switchHeroScent(3)" class="hero-thumb p-1.5 rounded-xl transition-all border-2 border-transparent hover:border-teal-300" title="Fresh Muse">
                                <img src="assets/images/fresh_muse.jpg" class="w-8 h-8 object-contain mix-blend-multiply">
                            </button>
                            <button onclick="switchHeroScent(4)" class="hero-thumb p-1.5 rounded-xl transition-all border-2 border-transparent hover:border-slate-400" title="Midnight Pulse">
                                <img src="assets/images/midnight_pulse.jpg" class="w-8 h-8 object-contain mix-blend-multiply">
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Fragrance Collection Section -->
    <section id="fragrances" class="py-20 bg-white relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="text-center max-w-3xl mx-auto space-y-4">
                <span class="text-xs font-bold tracking-widest text-brand-purple uppercase bg-purple-50 px-3.5 py-1.5 rounded-full border border-purple-100">Coleção Completa</span>
                <h2 class="font-serif text-3xl sm:text-4xl lg:text-5xl font-normal text-neutral-900">
                    Encontre a fragrância que conta a sua história
                </h2>
                <p class="text-neutral-600 text-base leading-relaxed font-light">
                    Cada fórmula foi desenhada para se fundir harmoniosamente à sua pele, exalando notas olfativas inspiradas nos grandes clássicos mundiais. Leve 1 por R$ <?= $formatPrice($promoSinglePrice) ?> ou leve 3 por R$ <?= $formatPrice($promoComboPrice) ?>.
                </p>
            </div>

            <div class="mt-10 flex flex-wrap items-center justify-center gap-2 sm:gap-3" id="scentFilters">
                <button onclick="filterProducts('all', event)" class="filter-btn active px-5 py-2 rounded-full text-xs sm:text-sm font-semibold transition-all bg-neutral-900 text-white shadow-xs">Todos (5)</button>
                <button onclick="filterProducts('feminino', event)" class="filter-btn px-5 py-2 rounded-full text-xs sm:text-sm font-medium transition-all bg-neutral-100 text-neutral-700 hover:bg-neutral-200">Femininos</button>
                <button onclick="filterProducts('masculino-unisex', event)" class="filter-btn px-5 py-2 rounded-full text-xs sm:text-sm font-medium transition-all bg-neutral-100 text-neutral-700 hover:bg-neutral-200">Masculinos & Unisex</button>
            </div>

            <div class="mt-12 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8" id="productsGrid">
                <!-- Injected dynamically via PHP REST API -->
            </div>
        </div>
    </section>

    <!-- Olfactory Pyramid Detail Modal -->
    <div id="notesModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-3xl max-w-lg w-full overflow-hidden shadow-2xl border border-rose-100 transform transition-all relative">
            
            <button onclick="closeNotesModal()" class="absolute top-4 right-4 z-10 bg-neutral-100 hover:bg-neutral-200 p-2 rounded-full text-neutral-600 transition-all">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>

            <div id="modalHeaderBg" class="p-8 text-neutral-900 text-center relative overflow-hidden bg-gradient-to-r from-pink-100 via-rose-50 to-pink-200">
                <div id="modalCategoryBadge" class="inline-block px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wider bg-white/80 backdrop-blur-md mb-2 text-rose-700"></div>
                <h3 id="modalProductName" class="font-serif text-3xl sm:text-4xl font-normal"></h3>
                <p id="modalTagline" class="text-sm opacity-90 font-light mt-1"></p>
                <div id="modalOlfactoryRef" class="mt-2 text-xs font-bold text-rose-700 bg-white/90 px-3 py-1 rounded-full inline-block border border-rose-200"></div>
            </div>

            <div class="p-6 sm:p-8 space-y-6">
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-widest text-neutral-400 mb-4 text-center">Pirâmide Olfativa Detalhada</h4>
                    
                    <div class="space-y-4">
                        <div class="p-3.5 rounded-2xl bg-amber-50/60 border border-amber-100/80 flex items-start gap-3">
                            <div class="p-2 rounded-xl bg-amber-100 text-amber-800 shrink-0">
                                <i data-lucide="sun" class="w-4 h-4"></i>
                            </div>
                            <div>
                                <span class="text-xs font-bold uppercase text-amber-900 block">Notas de Topo (Saída)</span>
                                <p id="modalTopNotes" class="text-xs text-neutral-700 mt-0.5 font-light"></p>
                            </div>
                        </div>

                        <div class="p-3.5 rounded-2xl bg-rose-50/60 border border-rose-100/80 flex items-start gap-3">
                            <div class="p-2 rounded-xl bg-rose-100 text-rose-800 shrink-0">
                                <i data-lucide="heart" class="w-4 h-4"></i>
                            </div>
                            <div>
                                <span class="text-xs font-bold uppercase text-rose-900 block">Notas de Coração (Corpo)</span>
                                <p id="modalHeartNotes" class="text-xs text-neutral-700 mt-0.5 font-light"></p>
                            </div>
                        </div>

                        <div class="p-3.5 rounded-2xl bg-purple-50/60 border border-purple-100/80 flex items-start gap-3">
                            <div class="p-2 rounded-xl bg-purple-100 text-purple-800 shrink-0">
                                <i data-lucide="sparkles" class="w-4 h-4"></i>
                            </div>
                            <div>
                                <span class="text-xs font-bold uppercase text-purple-900 block">Notas de Fundo (Fixação)</span>
                                <p id="modalBaseNotes" class="text-xs text-neutral-700 mt-0.5 font-light"></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-4 rounded-2xl bg-neutral-50 border border-neutral-100 text-xs text-neutral-600 flex items-center justify-between">
                    <div>
                        <span class="block font-semibold text-neutral-800">Sensação na pele</span>
                        <span id="modalSensationsText" class="font-light">Toque aveludado, feminino e envolvente</span>
                    </div>
                    <div id="modalVolumeBadge" class="font-mono text-neutral-500 font-semibold">236 mL</div>
                </div>

                <div class="flex items-center gap-3">
                    <button id="modalAddToCartBtn" class="flex-1 py-3.5 rounded-2xl bg-rose-500 text-white font-medium text-sm hover:bg-rose-600 transition-all shadow-md flex items-center justify-center gap-2">
                        <i data-lucide="shopping-bag" class="w-4 h-4"></i>
                        <span>Adicionar por R$ <?= $formatPrice($promoSinglePrice) ?></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Interactive Perfume Finder Quiz Section -->
    <section id="quiz" class="py-20 bg-gradient-to-br from-purple-900 via-neutral-900 to-rose-950 text-white relative overflow-hidden">
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-purple-500/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-rose-500/20 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center space-y-4">
                <span class="text-xs font-bold uppercase tracking-widest text-rose-300 bg-white/10 px-4 py-1.5 rounded-full backdrop-blur-md">Perfume Finder Exclusivo</span>
                <h2 class="font-serif text-3xl sm:text-4xl lg:text-5xl font-normal">
                    Não sabe qual fragrância escolher?
                </h2>
                <p class="text-rose-100/80 text-sm sm:text-base max-w-xl mx-auto font-light">
                    Responda 2 perguntas rápidas e nosso recomendador indicará a fragrância perfeita para seu momento e personalidade.
                </p>
            </div>

            <div class="mt-10 bg-white/10 backdrop-blur-xl rounded-3xl p-6 sm:p-10 border border-white/15 shadow-2xl">
                <div id="quizStepContainer">
                    <!-- Dynamic Quiz Steps rendered via JS -->
                </div>
            </div>
        </div>
    </section>

    <!-- Custom Bundle Section (Monte Seu Trio R$ <?= $formatPrice($promoComboPrice) ?>) -->
    <section id="combo" class="py-20 bg-brand-softBg relative border-t border-rose-100/60">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                
                <div class="lg:col-span-5 space-y-6">
                    <span class="text-xs font-bold uppercase tracking-widest text-amber-700 bg-amber-100/80 px-3.5 py-1.5 rounded-full border border-amber-200">
                        Monte Seu Trio Favorito
                    </span>

                    <h2 class="font-serif text-3xl sm:text-4xl font-normal text-neutral-900 leading-tight">
                        Monte Seu Trio por R$ <?= $formatPrice($promoComboPrice) ?>
                    </h2>

                    <p class="text-neutral-600 text-sm sm:text-base leading-relaxed font-light">
                        Ao selecionar 3 unidades do seu Body Splash favorito (ou combinando aromas diferentes), você economiza <strong>R$ <?= $formatPrice(($promoSinglePrice * 3) - $promoComboPrice) ?></strong> com Caixa de Presente inclusa.
                    </p>

                    <div class="p-4 rounded-2xl bg-white border border-rose-100 shadow-xs space-y-3">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-neutral-600"> Preço unitário individual:</span>
                            <span class="line-through text-neutral-400">3x R$ <?= $formatPrice($promoSinglePrice) ?> = R$ 149,70</span>
                        </div>
                        <div class="flex items-center justify-between text-base font-bold text-neutral-900 border-t border-neutral-100 pt-2">
                            <span class="flex items-center gap-1.5 text-rose-600">
                                <i data-lucide="tag" class="w-4 h-4"></i>
                                Valor do Kit Promo Trio:
                            </span>
                            <span class="text-xl text-rose-600 font-serif">R$ <?= $formatPrice($promoComboPrice) ?> <span class="text-xs font-sans text-neutral-500 font-normal">(Economia de R$ <?= $formatPrice(($promoSinglePrice * 3) - $promoComboPrice) ?>)</span></span>
                        </div>
                    </div>

                    <ul class="space-y-2.5 text-xs sm:text-sm text-neutral-700 font-medium">
                        <li class="flex items-center gap-2">
                            <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-500"></i>
                            <span>Escolha livremente entre as 5 fragrâncias</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-500"></i>
                            <span>Entrega via Correios (PAC e SEDEX)</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-500"></i>
                            <span>Caixa Gift Box Especial useLOVELY</span>
                        </li>
                    </ul>
                </div>

                <div class="lg:col-span-7 bg-white p-6 sm:p-8 rounded-3xl border border-rose-100 shadow-xl">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-serif text-2xl font-semibold text-neutral-900">Selecione os 3 itens do seu Kit</h3>
                        <span id="selectedCountBadge" class="text-xs font-sans font-semibold bg-rose-100 text-rose-700 px-3 py-1 rounded-full">0/3 selecionados</span>
                    </div>
                    <p class="text-xs text-neutral-500 mb-6">Você pode repetir sua fragrância favorita ou diversificar!</p>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4" id="builderSlots">
                        <!-- Injected dynamically -->
                    </div>

                    <div class="mt-6">
                        <span class="text-xs font-semibold text-neutral-600 block mb-2">Clique na fragrância para adicionar:</span>
                        <div class="grid grid-cols-2 sm:grid-cols-5 gap-2" id="builderAvailableGrid">
                            <!-- Injected dynamically -->
                        </div>
                    </div>

                    <div class="mt-8 pt-6 border-t border-neutral-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div>
                            <span class="text-xs text-neutral-500 block">Total do Kit (3 Itens):</span>
                            <span class="font-serif text-2xl font-bold text-neutral-900">R$ <?= $formatPrice($promoComboPrice) ?></span>
                        </div>
                        <button id="addBundleBtn" onclick="addBundleToCart()" disabled class="w-full sm:w-auto px-8 py-3.5 rounded-2xl bg-neutral-300 text-neutral-500 font-medium text-sm transition-all flex items-center justify-center gap-2 cursor-not-allowed">
                            <i data-lucide="package-plus" class="w-4 h-4"></i>
                            <span>Selecione 3 Frascos</span>
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Brand Value Section -->
    <section id="diferenciais" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16 space-y-3">
                <span class="text-xs font-bold uppercase tracking-widest text-rose-500 bg-rose-50 px-3.5 py-1.5 rounded-full">Qualidade Impecável</span>
                <h2 class="font-serif text-3xl sm:text-4xl font-normal text-neutral-900">
                    Por que o Body Splash useLOVELY é incomparável?
                </h2>
                <p class="text-neutral-600 text-sm sm:text-base font-light">
                    Combinando alta perfumaria com cuidado diário da pele, desenhamos frascos pensados nos mínimos detalhes.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="p-6 rounded-3xl bg-brand-softBg border border-rose-100/70 hover:shadow-lg transition-all text-center group">
                    <div class="w-14 h-14 mx-auto rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                        <i data-lucide="clock" class="w-7 h-7"></i>
                    </div>
                    <h3 class="font-serif text-xl font-semibold text-neutral-900 mb-2">Fixação Long-Lasting</h3>
                    <p class="text-xs text-neutral-600 leading-relaxed font-light">
                        Micro-cápsulas perfumadas que se reativam com a temperatura do corpo, mantendo o aroma vivo por até 12 horas.
                    </p>
                </div>

                <div class="p-6 rounded-3xl bg-brand-softBg border border-purple-100/70 hover:shadow-lg transition-all text-center group">
                    <div class="w-14 h-14 mx-auto rounded-2xl bg-purple-100 text-purple-600 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                        <i data-lucide="droplet" class="w-7 h-7"></i>
                    </div>
                    <h3 class="font-serif text-xl font-semibold text-neutral-900 mb-2">Com Extratos Botânicos</h3>
                    <p class="text-xs text-neutral-600 leading-relaxed font-light">
                        Enriquecido com Aloe Vera e Pantenol para hidratação leve e toque aveludado instantâneo.
                    </p>
                </div>

                <div class="p-6 rounded-3xl bg-brand-softBg border border-amber-100/70 hover:shadow-lg transition-all text-center group">
                    <div class="w-14 h-14 mx-auto rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                        <i data-lucide="leaf" class="w-7 h-7"></i>
                    </div>
                    <h3 class="font-serif text-xl font-semibold text-neutral-900 mb-2">Fórmula 100% Limpa</h3>
                    <p class="text-xs text-neutral-600 leading-relaxed font-light">
                        Livre de parabenos, ftalatos e sem testes em animais. Fórmula gentil aprovada dermatologicamente.
                    </p>
                </div>

                <div class="p-6 rounded-3xl bg-brand-softBg border border-teal-100/70 hover:shadow-lg transition-all text-center group">
                    <div class="w-14 h-14 mx-auto rounded-2xl bg-teal-100 text-teal-600 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                        <i data-lucide="sparkles" class="w-7 h-7"></i>
                    </div>
                    <h3 class="font-serif text-xl font-semibold text-neutral-900 mb-2">Vaporização em Névoa</h3>
                    <p class="text-xs text-neutral-600 leading-relaxed font-light">
                        Válvula de alta precisão importada que cria uma névoa ultrafina que envolve o corpo de forma uniforme.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Reviews Section -->
    <section id="avaliacoes" class="py-20 bg-gradient-to-b from-brand-softBg to-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-14 space-y-3">
                <div class="flex items-center justify-center gap-1 text-amber-400">
                    <i data-lucide="star" class="w-5 h-5 fill-amber-400"></i>
                    <i data-lucide="star" class="w-5 h-5 fill-amber-400"></i>
                    <i data-lucide="star" class="w-5 h-5 fill-amber-400"></i>
                    <i data-lucide="star" class="w-5 h-5 fill-amber-400"></i>
                    <i data-lucide="star" class="w-5 h-5 fill-amber-400"></i>
                </div>
                <h2 class="font-serif text-3xl sm:text-4xl font-normal text-neutral-900">
                    O que dizem nossas clientes encantadas
                </h2>
                <p class="text-neutral-600 text-sm sm:text-base font-light">
                    Mais de 2.400 mulheres já encontraram sua assinatura perfumada com a useLOVELY.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="p-6 rounded-3xl bg-white border border-rose-100 shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex gap-1 text-amber-400">
                                ★★★★★
                            </div>
                            <span class="text-[11px] text-emerald-600 font-semibold bg-emerald-50 px-2.5 py-0.5 rounded-full flex items-center gap-1">
                                <i data-lucide="check" class="w-3 h-3"></i> Compra Verificada
                            </span>
                        </div>
                        <p class="text-xs sm:text-sm text-neutral-700 leading-relaxed italic">
                            "Comprei o Velvet Bloom e o Purple Kiss. O aroma de Velvet Bloom é simplesmente perfeito! Lembra muito o La Vie Est Belle, todo mundo me pergunta qual perfume estou usando."
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-neutral-100 flex items-center justify-between">
                        <div>
                            <span class="font-semibold text-neutral-900 text-sm block">Camila Rodrigues</span>
                            <span class="text-[11px] text-neutral-400">São Paulo, SP</span>
                        </div>
                        <span class="text-[11px] font-semibold text-rose-500 bg-rose-50 px-2 py-0.5 rounded-md">Velvet Bloom</span>
                    </div>
                </div>

                <div class="p-6 rounded-3xl bg-white border border-amber-100 shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex gap-1 text-amber-400">
                                ★★★★★
                            </div>
                            <span class="text-[11px] text-emerald-600 font-semibold bg-emerald-50 px-2.5 py-0.5 rounded-full flex items-center gap-1">
                                <i data-lucide="check" class="w-3 h-3"></i> Compra Verificada
                            </span>
                        </div>
                        <p class="text-xs sm:text-sm text-neutral-700 leading-relaxed italic">
                            "Golden Glow é amor à primeira borrifada! Lembra o Erba Pura de Xerjoff, muito elegante. A névoa é super fina e dura o dia todinho."
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-neutral-100 flex items-center justify-between">
                        <div>
                            <span class="font-semibold text-neutral-900 text-sm block">Beatriz Santos</span>
                            <span class="text-[11px] text-neutral-400">Rio de Janeiro, RJ</span>
                        </div>
                        <span class="text-[11px] font-semibold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-md">Golden Glow</span>
                    </div>
                </div>

                <div class="p-6 rounded-3xl bg-white border border-teal-100 shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex gap-1 text-amber-400">
                                ★★★★★
                            </div>
                            <span class="text-[11px] text-emerald-600 font-semibold bg-emerald-50 px-2.5 py-0.5 rounded-full flex items-center gap-1">
                                <i data-lucide="check" class="w-3 h-3"></i> Compra Verificada
                            </span>
                        </div>
                        <p class="text-xs sm:text-sm text-neutral-700 leading-relaxed italic">
                            "Aproveitei o Kit com 3 por R$ <?= $formatPrice($promoComboPrice) ?> e peguei Fresh Muse, Midnight Pulse e Golden Glow. A inspiração do Bleu de Chanel no Midnight Pulse ficou sensacional!"
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-neutral-100 flex items-center justify-between">
                        <div>
                            <span class="font-semibold text-neutral-900 text-sm block">Juliana Ferreira</span>
                            <span class="text-[11px] text-neutral-400">Belo Horizonte, MG</span>
                        </div>
                        <span class="text-[11px] font-semibold text-teal-600 bg-teal-50 px-2 py-0.5 rounded-md">Combo Trio</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Slide-over Shopping Cart Drawer with Correios Shipping Calculator -->
    <div id="cartDrawer" class="fixed inset-0 z-50 hidden">
        <div onclick="closeCart()" class="absolute inset-0 bg-black/60 backdrop-blur-xs"></div>
        <div class="absolute inset-y-0 right-0 max-w-md w-full bg-white shadow-2xl flex flex-col justify-between p-6 sm:p-8">
            <div class="space-y-6 overflow-y-auto">
                <div class="flex items-center justify-between border-b border-neutral-100 pb-4">
                    <h3 class="font-serif text-2xl font-bold text-neutral-900">Sua Sacola de Compras</h3>
                    <button onclick="closeCart()" class="p-2 text-neutral-400 hover:text-neutral-700">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <!-- Items List -->
                <div id="cartItemsList" class="space-y-4 divide-y divide-neutral-100">
                    <!-- Injected dynamically -->
                </div>

                <!-- Correios Shipping Calculator Box -->
                <div class="p-4 rounded-2xl bg-neutral-50 border border-neutral-200 space-y-3 text-xs">
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-neutral-900 flex items-center gap-1.5">
                            <i data-lucide="truck" class="w-4 h-4 text-rose-500"></i>
                            Calcular Frete Correios
                        </span>
                        <span id="cartShippingStatus" class="text-[10px] font-semibold text-rose-500"></span>
                    </div>

                    <div class="flex gap-2">
                        <input type="text" id="cartCepInput" placeholder="CEP (ex: 01001-000)" maxlength="9" class="w-full px-3 py-2 rounded-xl border border-neutral-300 focus:outline-none focus:border-rose-500 font-mono">
                        <button type="button" onclick="calculateCartShipping()" class="px-3.5 py-2 rounded-xl bg-neutral-900 hover:bg-neutral-800 text-white font-semibold shrink-0">Calcular</button>
                    </div>

                    <!-- Shipping Radio Options -->
                    <div id="cartShippingOptionsList" class="hidden space-y-2 pt-2 border-t border-neutral-200">
                        <!-- Options injected dynamically -->
                    </div>
                </div>
            </div>

            <div class="pt-6 border-t border-neutral-100 space-y-4">
                <div class="space-y-2 text-xs">
                    <div class="flex justify-between text-neutral-500">
                        <span>Subtotal:</span>
                        <span id="cartSubtotal">R$ 0,00</span>
                    </div>
                    <div class="flex justify-between text-neutral-500">
                        <span>Frete Correios:</span>
                        <span id="cartShippingDisplay" class="font-medium text-neutral-700">Informe seu CEP</span>
                    </div>
                    <div class="flex justify-between text-neutral-900 font-bold text-base pt-2 border-t border-neutral-100">
                        <span>Total:</span>
                        <span id="cartTotal">R$ 0,00</span>
                    </div>
                </div>

                <button onclick="openCheckoutModal()" class="w-full py-4 bg-rose-500 hover:bg-rose-600 text-white font-semibold text-sm rounded-2xl transition-all shadow-md flex items-center justify-center gap-2">
                    <i data-lucide="credit-card" class="w-4 h-4"></i>
                    <span>Ir para o Checkout</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Customer Auth Modal (Criar Conta & Login 100% MySQL) -->
    <div id="authModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 relative shadow-2xl">
            <button onclick="closeAuthModal()" class="absolute top-4 right-4 p-2 text-neutral-400 hover:text-neutral-700">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>

            <div class="flex border-b border-neutral-200 mb-6">
                <button id="tabLoginBtn" onclick="switchAuthTab('login')" class="flex-1 py-2.5 text-xs font-bold uppercase tracking-wider border-b-2 border-rose-500 text-rose-600">Entrar</button>
                <button id="tabRegisterBtn" onclick="switchAuthTab('register')" class="flex-1 py-2.5 text-xs font-bold uppercase tracking-wider border-b-2 border-transparent text-neutral-400 hover:text-neutral-700">Criar Conta</button>
            </div>

            <div id="authErrorMsg" class="hidden mb-4 p-3 rounded-xl bg-rose-50 border border-rose-200 text-xs text-rose-600 font-semibold"></div>

            <form id="customerLoginForm" class="space-y-4 text-xs">
                <div>
                    <label class="block font-semibold text-neutral-700 mb-1">E-mail</label>
                    <input type="email" id="custLoginEmail" required placeholder="seu@email.com" class="w-full px-3.5 py-2.5 rounded-xl border border-neutral-300 focus:outline-none focus:border-rose-500">
                </div>
                <div>
                    <label class="block font-semibold text-neutral-700 mb-1">Senha</label>
                    <input type="password" id="custLoginPassword" required placeholder="••••••••" class="w-full px-3.5 py-2.5 rounded-xl border border-neutral-300 focus:outline-none focus:border-rose-500">
                </div>
                <button type="submit" class="w-full py-3 bg-neutral-900 hover:bg-neutral-800 text-white font-semibold rounded-xl transition-all shadow-md">
                    Entrar na Minha Conta
                </button>
                <div class="text-center mt-2">
                    <a href="#" onclick="openForgotPasswordModal(event)" class="text-rose-500 hover:text-rose-600 font-semibold underline decoration-transparent hover:decoration-rose-500 transition-all">Esqueci minha senha</a>
                </div>
            </form>

            <form id="customerRegisterForm" class="hidden space-y-4 text-xs">
                <div>
                    <label class="block font-semibold text-neutral-700 mb-1">Nome Completo</label>
                    <input type="text" id="custRegName" required placeholder="Maria Silva" class="w-full px-3.5 py-2.5 rounded-xl border border-neutral-300 focus:outline-none focus:border-rose-500">
                </div>
                <div>
                    <label class="block font-semibold text-neutral-700 mb-1">E-mail</label>
                    <input type="email" id="custRegEmail" required placeholder="seu@email.com" class="w-full px-3.5 py-2.5 rounded-xl border border-neutral-300 focus:outline-none focus:border-rose-500">
                </div>
                <div>
                    <label class="block font-semibold text-neutral-700 mb-1">Telefone / WhatsApp</label>
                    <input type="tel" id="custRegPhone" required placeholder="(11) 99999-9999" class="w-full px-3.5 py-2.5 rounded-xl border border-neutral-300 focus:outline-none focus:border-rose-500">
                </div>
                <div>
                    <label class="block font-semibold text-neutral-700 mb-1">Senha</label>
                    <input type="password" id="custRegPassword" required placeholder="••••••••" class="w-full px-3.5 py-2.5 rounded-xl border border-neutral-300 focus:outline-none focus:border-rose-500">
                </div>
                <button type="submit" class="w-full py-3 bg-rose-500 hover:bg-rose-600 text-white font-semibold rounded-xl transition-all shadow-md">
                    Criar Minha Conta
                </button>
            </form>
        </div>
    </div>

    <!-- Forgot Password Modal -->
    <div id="forgotPasswordModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 relative shadow-2xl">
            <button onclick="closeForgotPasswordModal()" class="absolute top-4 right-4 p-2 text-neutral-400 hover:text-neutral-700">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
            <div class="text-center mb-6">
                <h3 class="font-serif text-2xl font-bold text-neutral-900">Recuperar Senha</h3>
                <p class="text-xs text-neutral-500 mt-1">Digite seu e-mail para receber o link de recuperação</p>
            </div>
            <div id="forgotPasswordErrorMsg" class="hidden mb-4 p-3 rounded-xl bg-rose-50 border border-rose-200 text-xs text-rose-600 font-semibold"></div>
            <div id="forgotPasswordSuccessMsg" class="hidden mb-4 p-3 rounded-xl bg-green-50 border border-green-200 text-xs text-green-700 font-semibold"></div>
            <form id="forgotPasswordForm" class="space-y-4 text-xs">
                <div>
                    <label class="block font-semibold text-neutral-700 mb-1">E-mail</label>
                    <input type="email" id="forgotEmail" required placeholder="seu@email.com" class="w-full px-3.5 py-2.5 rounded-xl border border-neutral-300 focus:outline-none focus:border-rose-500">
                </div>
                <button type="submit" class="w-full py-3 bg-neutral-900 hover:bg-neutral-800 text-white font-semibold rounded-xl transition-all shadow-md">
                    Enviar Link de Recuperação
                </button>
                <div class="text-center mt-2">
                    <a href="#" onclick="backToLoginModal(event)" class="text-neutral-500 hover:text-neutral-700 font-semibold transition-all">Voltar para o Login</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Reset Password Modal -->
    <div id="resetPasswordModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 relative shadow-2xl">
            <div class="text-center mb-6">
                <h3 class="font-serif text-2xl font-bold text-neutral-900">Nova Senha</h3>
                <p class="text-xs text-neutral-500 mt-1">Crie uma nova senha para sua conta</p>
            </div>
            <div id="resetPasswordErrorMsg" class="hidden mb-4 p-3 rounded-xl bg-rose-50 border border-rose-200 text-xs text-rose-600 font-semibold"></div>
            <div id="resetPasswordSuccessMsg" class="hidden mb-4 p-3 rounded-xl bg-green-50 border border-green-200 text-xs text-green-700 font-semibold"></div>
            <form id="resetPasswordForm" class="space-y-4 text-xs">
                <input type="hidden" id="resetTokenInput">
                <div>
                    <label class="block font-semibold text-neutral-700 mb-1">Nova Senha</label>
                    <input type="password" id="resetNewPassword" required placeholder="••••••••" class="w-full px-3.5 py-2.5 rounded-xl border border-neutral-300 focus:outline-none focus:border-rose-500">
                </div>
                <button type="submit" class="w-full py-3 bg-rose-500 hover:bg-rose-600 text-white font-semibold rounded-xl transition-all shadow-md">
                    Salvar Nova Senha
                </button>
                <div class="text-center mt-2">
                    <a href="#" onclick="window.location.href='/'" class="text-neutral-500 hover:text-neutral-700 font-semibold transition-all">Ir para o Início</a>
                </div>
            </form>
        </div>
    </div>

    <!-- My Account Modal -->
    <div id="myAccountModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
        <div class="bg-white rounded-3xl max-w-2xl w-full p-6 sm:p-8 relative shadow-2xl max-h-[90vh] overflow-y-auto">
            <button onclick="closeMyAccountModal()" class="absolute top-4 right-4 p-2 text-neutral-400 hover:text-neutral-700">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>

            <div class="text-center mb-6">
                <div class="w-12 h-12 bg-neutral-100 text-neutral-600 rounded-2xl flex items-center justify-center mx-auto mb-1">
                    <i data-lucide="user" class="w-6 h-6"></i>
                </div>
                <h3 class="font-serif text-2xl font-bold text-neutral-900">Minha Conta</h3>
                <p class="text-xs text-neutral-500">Gerencie seus dados e endereços</p>
            </div>

            <div class="flex border-b border-neutral-200 mb-6">
                <button id="tabProfileBtn" onclick="switchAccountTab('profile')" class="flex-1 py-2.5 text-xs font-bold uppercase tracking-wider border-b-2 border-rose-500 text-rose-600">Meus Dados</button>
                <button id="tabAddressesBtn" onclick="switchAccountTab('addresses')" class="flex-1 py-2.5 text-xs font-bold uppercase tracking-wider border-b-2 border-transparent text-neutral-400 hover:text-neutral-700">Meus Endereços</button>
            </div>

            <div id="accountErrorMsg" class="hidden mb-4 p-3 rounded-xl bg-rose-50 border border-rose-200 text-xs text-rose-600 font-semibold"></div>
            <div id="accountSuccessMsg" class="hidden mb-4 p-3 rounded-xl bg-green-50 border border-green-200 text-xs text-green-700 font-semibold"></div>

            <!-- Profile Form -->
            <form id="profileForm" class="space-y-4 text-xs">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-semibold text-neutral-700 mb-1">Nome Completo</label>
                        <input type="text" id="accName" required class="w-full px-3.5 py-2.5 rounded-xl border border-neutral-300 focus:outline-none focus:border-rose-500">
                    </div>
                    <div>
                        <label class="block font-semibold text-neutral-700 mb-1">CPF</label>
                        <input type="text" id="accCpf" class="w-full px-3.5 py-2.5 rounded-xl border border-neutral-300 focus:outline-none focus:border-rose-500">
                    </div>
                    <div>
                        <label class="block font-semibold text-neutral-700 mb-1">Telefone</label>
                        <input type="tel" id="accPhone" required class="w-full px-3.5 py-2.5 rounded-xl border border-neutral-300 focus:outline-none focus:border-rose-500">
                    </div>
                    <div>
                        <label class="block font-semibold text-neutral-700 mb-1">E-mail (Apenas Leitura)</label>
                        <input type="email" id="accEmail" readonly class="w-full px-3.5 py-2.5 rounded-xl border border-neutral-200 bg-neutral-50 text-neutral-500">
                    </div>
                </div>
                <div class="pt-2">
                    <button type="submit" class="w-full py-3 bg-neutral-900 hover:bg-neutral-800 text-white font-semibold rounded-xl transition-all shadow-md">
                        Salvar Alterações
                    </button>
                </div>
            </form>

            <!-- Addresses Section -->
            <div id="addressesSection" class="hidden space-y-4 text-xs">
                <div class="flex justify-between items-center mb-2">
                    <h4 class="font-bold text-neutral-800">Endereços Salvos</h4>
                    <button onclick="openAddAddressForm()" class="text-rose-500 font-bold flex items-center hover:text-rose-600">
                        <i data-lucide="plus" class="w-4 h-4 mr-1"></i> Adicionar
                    </button>
                </div>
                
                <div id="addressesList" class="space-y-3">
                    <!-- Endereços carregados via JS -->
                </div>

                <!-- Add Address Form (Hidden by default) -->
                <form id="addAddressForm" class="hidden mt-4 p-4 border border-neutral-200 rounded-2xl bg-neutral-50 space-y-3">
                    <h5 class="font-bold text-neutral-700">Novo Endereço</h5>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <input type="text" id="addCep" required placeholder="CEP" onblur="fetchAddressForNewAddress()" class="px-3.5 py-2.5 rounded-xl border border-neutral-300 focus:outline-none focus:border-rose-500">
                        <input type="text" id="addStreet" required placeholder="Rua / Avenida" class="px-3.5 py-2.5 rounded-xl border border-neutral-300 focus:outline-none focus:border-rose-500">
                        <input type="text" id="addNumber" required placeholder="Número" class="px-3.5 py-2.5 rounded-xl border border-neutral-300 focus:outline-none focus:border-rose-500">
                        <input type="text" id="addComplement" placeholder="Complemento" class="px-3.5 py-2.5 rounded-xl border border-neutral-300 focus:outline-none focus:border-rose-500">
                        <input type="text" id="addNeighborhood" required placeholder="Bairro" class="px-3.5 py-2.5 rounded-xl border border-neutral-300 focus:outline-none focus:border-rose-500">
                        <div class="flex gap-2">
                            <input type="text" id="addCity" required placeholder="Cidade" class="flex-1 px-3.5 py-2.5 rounded-xl border border-neutral-300 focus:outline-none focus:border-rose-500">
                            <input type="text" id="addState" required placeholder="UF" maxlength="2" class="w-16 px-3.5 py-2.5 rounded-xl border border-neutral-300 focus:outline-none focus:border-rose-500 uppercase">
                        </div>
                    </div>
                    <div class="flex gap-2 pt-2">
                        <button type="button" onclick="closeAddAddressForm()" class="flex-1 py-2.5 bg-neutral-200 hover:bg-neutral-300 text-neutral-800 font-semibold rounded-xl transition-all">Cancelar</button>
                        <button type="submit" class="flex-1 py-2.5 bg-rose-500 hover:bg-rose-600 text-white font-semibold rounded-xl transition-all shadow-md">Salvar Endereço</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Complete Checkout Modal (ViaCEP + Correios + Mercado Pago) -->
    <div id="checkoutModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 relative shadow-2xl max-h-[90vh] overflow-y-auto">
            <button onclick="closeCheckoutModal()" class="absolute top-4 right-4 p-2 text-neutral-400 hover:text-neutral-700">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>

            <form id="checkoutFullForm" class="space-y-6 text-xs">
                <div class="text-center space-y-1">
                    <div class="w-12 h-12 bg-rose-100 text-rose-600 rounded-2xl flex items-center justify-center mx-auto mb-1">
                        <i data-lucide="truck" class="w-6 h-6"></i>
                    </div>
                    <h3 class="font-serif text-2xl font-bold text-neutral-900">Endereço de Entrega & Pagamento</h3>
                    <p class="text-xs text-neutral-500">Preencha seus dados para finalizar a compra online</p>
                </div>

                <div class="p-4 rounded-2xl bg-neutral-50 border border-neutral-200 space-y-3">
                    <span class="font-bold text-neutral-900 block text-xs">1. Dados Pessoais</span>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <input type="text" id="chkName" required placeholder="Nome Completo" class="px-3.5 py-2.5 rounded-xl border border-neutral-300 focus:outline-none focus:border-rose-500">
                        <input type="email" id="chkEmail" required placeholder="E-mail" class="px-3.5 py-2.5 rounded-xl border border-neutral-300 focus:outline-none focus:border-rose-500">
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <input type="tel" id="chkPhone" required placeholder="Telefone / WhatsApp" class="px-3.5 py-2.5 rounded-xl border border-neutral-300 focus:outline-none focus:border-rose-500">
                        <input type="text" id="chkCpf" required placeholder="CPF (ex: 000.000.000-00)" maxlength="14" class="px-3.5 py-2.5 rounded-xl border border-neutral-300 focus:outline-none focus:border-rose-500 font-mono">
                    </div>
                </div>

                <div class="p-4 rounded-2xl bg-neutral-50 border border-neutral-200 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-neutral-900 block text-xs">2. Endereço de Entrega</span>
                        <span id="cepStatusMsg" class="text-[10px] font-semibold text-rose-500"></span>
                    </div>

                    <div id="checkoutSavedAddressesContainer" class="hidden mb-1">
                        <select id="checkoutSavedAddresses" onchange="fillSavedAddress(this.value)" class="w-full px-3.5 py-2.5 rounded-xl border border-neutral-300 focus:outline-none focus:border-rose-500 text-sm bg-white cursor-pointer hover:border-rose-300 transition-colors">
                            <option value="">Usar um endereço salvo...</option>
                        </select>
                    </div>

                    <div class="flex gap-2">
                        <input type="text" id="chkCep" required placeholder="CEP (ex: 01001-000)" maxlength="9" class="w-full px-3.5 py-2.5 rounded-xl border border-neutral-300 focus:outline-none focus:border-rose-500 font-mono">
                        <button type="button" onclick="lookupCep()" class="px-4 py-2.5 rounded-xl bg-neutral-900 hover:bg-neutral-800 text-white font-semibold shrink-0">Buscar CEP</button>
                    </div>

                    <div class="grid grid-cols-3 gap-2">
                        <input type="text" id="chkStreet" required placeholder="Rua / Logradouro" class="col-span-2 px-3.5 py-2.5 rounded-xl border border-neutral-300 focus:outline-none focus:border-rose-500">
                        <input type="text" id="chkNumber" required placeholder="Número" class="px-3.5 py-2.5 rounded-xl border border-neutral-300 focus:outline-none focus:border-rose-500">
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <input type="text" id="chkComplement" placeholder="Complemento / Apto" class="px-3.5 py-2.5 rounded-xl border border-neutral-300 focus:outline-none focus:border-rose-500">
                        <input type="text" id="chkNeighborhood" required placeholder="Bairro" class="px-3.5 py-2.5 rounded-xl border border-neutral-300 focus:outline-none focus:border-rose-500">
                    </div>

                    <div class="grid grid-cols-3 gap-2">
                        <input type="text" id="chkCity" required placeholder="Cidade" class="col-span-2 px-3.5 py-2.5 rounded-xl border border-neutral-300 focus:outline-none focus:border-rose-500">
                        <input type="text" id="chkState" required placeholder="UF" maxlength="2" class="px-3.5 py-2.5 rounded-xl border border-neutral-300 focus:outline-none focus:border-rose-500 uppercase">
                    </div>
                </div>

                <!-- Frete Selection Box in Checkout -->
                <div class="p-4 rounded-2xl bg-neutral-50 border border-neutral-200 space-y-3">
                    <span class="font-bold text-neutral-900 block text-xs">3. Opção de Envio Correios</span>
                    <div id="checkoutShippingOptions" class="space-y-2">
                        <p class="text-neutral-500 text-[11px]">Informe o CEP acima para visualizar as opções dos Correios.</p>
                    </div>
                </div>

                <!-- Mercado Pago Info -->
                <div class="p-4 rounded-2xl bg-blue-50/70 border border-blue-200 space-y-2 text-blue-900">
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded-md bg-blue-600 text-white flex items-center justify-center font-bold text-[10px]">MP</div>
                        <span class="font-bold text-xs">Pagamento Seguro via Mercado Pago</span>
                    </div>
                    <p class="text-[11px] text-blue-700 leading-relaxed font-light">
                        Você poderá pagar via <strong>PIX</strong>, <strong>Cartão de Crédito em até 6x</strong>, Boleto ou Saldo do Mercado Pago no ambiente 100% seguro do Checkout Pro.
                    </p>
                </div>

                <div id="checkoutErrorMsg" class="hidden p-3 rounded-xl bg-rose-50 border border-rose-200 text-xs text-rose-600 font-semibold"></div>

                <div class="pt-2 flex items-center justify-between">
                    <div>
                        <span class="text-[10px] text-neutral-400 uppercase font-semibold block">Total a Pagar</span>
                        <span id="chkFinalTotal" class="font-serif text-2xl font-bold text-rose-600">R$ 0,00</span>
                    </div>

                    <button type="submit" id="submitCheckoutBtn" class="px-8 py-3.5 bg-rose-500 hover:bg-rose-600 disabled:bg-neutral-400 text-white font-bold rounded-2xl transition-all shadow-lg flex items-center gap-2">
                        <i data-lucide="shield-check" class="w-4 h-4"></i>
                        <span id="submitCheckoutBtnText">IR PARA PAGAMENTO</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Customer Orders History Modal -->
    <div id="userOrdersModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
        <div class="bg-white rounded-3xl max-w-2xl w-full p-6 sm:p-8 relative shadow-2xl max-h-[90vh] overflow-y-auto">
            <button onclick="closeUserOrdersModal()" class="absolute top-4 right-4 p-2 text-neutral-400 hover:text-neutral-700">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>

            <h3 class="font-serif text-2xl font-bold text-neutral-900 mb-1">Meus Pedidos Realizados</h3>
            <p class="text-xs text-neutral-500 mb-6">Acompanhe o status e a entrega de todas as suas compras online na useLOVELY</p>

            <div id="userOrdersList" class="space-y-4">
                <!-- Injected dynamically -->
            </div>
        </div>
    </div>

    <!-- Floating WhatsApp Contact Button -->
    <a href="https://wa.me/5527996968825?text=Olá!%20Gostaria%20de%20tirar%20uma%20dúvida%20sobre%20os%20Body%20Splashes%20da%20useLOVELY." target="_blank" rel="noopener noreferrer" class="fixed bottom-6 right-6 z-40 bg-emerald-500 hover:bg-emerald-600 text-white p-3.5 rounded-full shadow-2xl transition-all duration-300 hover:scale-110 flex items-center justify-center gap-2 group" title="Falar no WhatsApp (27) 99696-8825">
        <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
        </svg>
        <span class="max-w-0 overflow-hidden whitespace-nowrap group-hover:max-w-xs transition-all duration-500 ease-in-out text-xs font-bold pl-1">WhatsApp (27) 99696-8825</span>
    </a>

    <!-- Footer Section -->
    <footer class="bg-neutral-900 text-white pt-16 pb-12 border-t border-neutral-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10 pb-12 border-b border-neutral-800">
                
                <div class="lg:col-span-2 space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-rose-400/20 flex items-center justify-center">
                            <svg viewBox="0 0 100 100" class="w-6 h-6">
                                <path d="M50,15 C45,35 25,45 15,55 C30,60 45,55 50,85 C55,55 70,60 85,55 C75,45 55,35 50,15 Z" fill="#E8A5B8"/>
                            </svg>
                        </div>
                        <span class="font-serif text-2xl font-semibold tracking-wider text-white">
                            use<span class="font-bold uppercase tracking-widest text-xl ml-1 text-rose-300">LOVELY</span>
                        </span>
                    </div>
                    <p class="text-xs text-neutral-400 leading-relaxed font-light max-w-sm">
                        High-End Body Splashes criados para despertar sentimentos, destacar sua personalidade e proporcionar momentos inesquecíveis todos os dias.
                    </p>
                </div>

                <div class="space-y-3">
                    <h4 class="font-serif text-lg font-semibold text-rose-200">Nossas Fragrâncias</h4>
                    <ul class="space-y-2 text-xs text-neutral-400 font-light">
                        <li><a href="#fragrances" onclick="filterProducts('all')" class="hover:text-white transition-colors">Velvet Bloom</a></li>
                        <li><a href="#fragrances" onclick="filterProducts('all')" class="hover:text-white transition-colors">Purple Kiss</a></li>
                        <li><a href="#fragrances" onclick="filterProducts('all')" class="hover:text-white transition-colors">Golden Glow</a></li>
                        <li><a href="#fragrances" onclick="filterProducts('all')" class="hover:text-white transition-colors">Fresh Muse</a></li>
                        <li><a href="#fragrances" onclick="filterProducts('all')" class="hover:text-white transition-colors">Midnight Pulse</a></li>
                    </ul>
                </div>

                <div class="space-y-3">
                    <h4 class="font-serif text-lg font-semibold text-rose-200">Atendimento WhatsApp</h4>
                    <ul class="space-y-2 text-xs text-neutral-400 font-light">
                        <li>
                            <a href="https://wa.me/5527996968825?text=Olá!%20Gostaria%20de%20tirar%20uma%20dúvida%20sobre%20os%20Body%20Splashes%20da%20useLOVELY." target="_blank" rel="noopener noreferrer" class="hover:text-white transition-colors text-emerald-400 font-semibold flex items-center gap-1.5">
                                <i data-lucide="message-circle" class="w-4 h-4"></i> WhatsApp: (27) 99696-8825
                            </a>
                        </li>
                        <li><a href="#" class="hover:text-white transition-colors">Central de Ajuda</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Rastreie seu Pedido</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Política de Entregas</a></li>
                    </ul>
                </div>

                <div class="space-y-3">
                    <h4 class="font-serif text-lg font-semibold text-rose-200">Ganhe 10% OFF</h4>
                    <p class="text-xs text-neutral-400 font-light">
                        Cadastre-se para receber ofertas secretas e lançamentos no seu e-mail:
                    </p>
                    <form onsubmit="event.preventDefault(); alert('Obrigado por se inscrever! Seu cupom LOVELY10 é de 10% OFF.');" class="space-y-2">
                        <input type="email" placeholder="Seu melhor e-mail..." required class="w-full px-3.5 py-2.5 rounded-xl bg-neutral-800 border border-neutral-700 text-xs text-white placeholder-neutral-500 focus:outline-none focus:border-rose-400">
                        <button type="submit" class="w-full py-2.5 bg-rose-500 hover:bg-rose-600 text-white font-semibold text-xs rounded-xl transition-all">
                            Inscrever-se
                        </button>
                    </form>
                </div>

            </div>

            <div class="pt-8 flex flex-col md:flex-row items-center justify-between gap-4 text-xs text-neutral-500 font-light">
                <div class="space-y-1 text-center md:text-left">
                    <p>© 2026 useLOVELY Cosmetics. Todos os direitos reservados.</p>
                </div>
                <div class="flex items-center gap-4">
                    <span>Mercado Pago</span> • <span>Correios PAC & SEDEX</span> • <span>PIX</span> • <span>Cartão</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- 100% PHP & MySQL Engine with Correios API -->
    <script>
        window.CONFIG = {
            singlePrice: <?= json_encode($promoSinglePrice) ?>,
            comboPrice: <?= json_encode($promoComboPrice) ?>
        };
        const SINGLE_PRICE = window.CONFIG.singlePrice;
        const COMBO_PRICE = window.CONFIG.comboPrice;
        let PRODUCTS = [];
        let cart = [];
        let currentUser = null;
        let builderSelection = [null, null, null];
        let currentQuizStep = 0;
        let quizAnswers = [];

        let currentShippingCost = 0;
        let currentShippingMethod = '';
        let availableShippingOptions = [];

        const QUIZ_QUESTIONS = [
            {
                id: 1,
                question: 'Qual é o seu clima ou momento favorito do dia?',
                options: [
                    { label: 'Um passeio ao ar livre em uma manhã florida', productId: 'velvet-bloom' },
                    { label: 'Um encontro especial no fim de tarde', productId: 'purple-kiss' },
                    { label: 'Um dia ensolarado com brisa quente', productId: 'golden-glow' },
                    { label: 'Sair da academia ou um mergulho revigorante', productId: 'fresh-muse' },
                    { label: 'Uma festa ou evento marcante à noite', productId: 'midnight-pulse' }
                ]
            },
            {
                id: 2,
                question: 'Qual família olfativa mais chama sua atenção?',
                options: [
                    { label: 'Florais delicados com toque suave de rosas', productId: 'velvet-bloom' },
                    { label: 'Adocicados envolventes com baunilha ou frutados', productId: 'purple-kiss' },
                    { label: 'Cálidos, âmbar e notas ensolaradas', productId: 'golden-glow' },
                    { label: 'Aquáticos, cítricos e chá verde leve', productId: 'fresh-muse' },
                    { label: 'Intensos, amadeirados e ambarados', productId: 'midnight-pulse' }
                ]
            }
        ];

        // Check Customer Session
        async function checkCustomerSession() {
            try {
                const res = await fetch('api/auth_check.php');
                const result = await res.json();
                if (result.loggedIn && result.user) {
                    currentUser = result.user;
                } else {
                    currentUser = null;
                }
                updateCustomerNavUI();
            } catch (e) {
                console.error("Erro na sessão:", e);
            }
        }

        function updateCustomerNavUI() {
            const container = document.getElementById('customerAuthContainer');
            if (currentUser) {
                document.getElementById('chkEmail').value = currentUser.email;
                if (currentUser.name) document.getElementById('chkName').value = currentUser.name;
                if (currentUser.phone) document.getElementById('chkPhone').value = currentUser.phone;
                if (currentUser.cpf) document.getElementById('chkCpf').value = currentUser.cpf;

                container.innerHTML = `
                    <div class="flex items-center gap-2">
                        <button onclick="openUserOrdersModal()" class="px-3 py-1.5 rounded-full bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-semibold transition-all border border-rose-200" title="Meus Pedidos">
                            <i data-lucide="shopping-bag" class="w-4 h-4"></i>
                        </button>
                        <button onclick="openMyAccountModal()" class="px-3 py-1.5 rounded-full bg-white hover:bg-neutral-100 text-neutral-700 text-xs font-semibold transition-all border border-neutral-200 shadow-xs" title="Minha Conta">
                            <i data-lucide="user" class="w-4 h-4 text-rose-600"></i>
                            <span class="hidden sm:inline">Minha Conta</span>
                        </button>
                        <button onclick="logoutCustomer()" class="p-2 text-neutral-400 hover:text-neutral-700" title="Sair">
                            <i data-lucide="log-out" class="w-4 h-4"></i>
                        </button>
                    </div>
                `;
            } else {
                container.innerHTML = `
                    <button onclick="openAuthModal('login')" class="flex items-center gap-2 px-3.5 py-2 rounded-full bg-white hover:bg-neutral-100 text-neutral-700 text-xs font-semibold transition-all border border-neutral-200 shadow-xs">
                        <i data-lucide="user" class="w-4 h-4 text-rose-600"></i>
                        <span class="hidden sm:inline">Entrar / Criar Conta</span>
                    </button>
                `;
            }
            lucide.createIcons();
        }

        const THEME_MAP = {
            'rose': { border: 'border-rose-500', bg: 'bg-rose-50', text: 'text-rose-600', badge: 'bg-rose-100/80 text-rose-700 border-rose-200', ping: 'bg-rose-400', section: 'via-rose-50/40', box: 'from-pink-100/60 via-rose-50/50 to-purple-50/60', blob1: 'bg-pink-200/40', blob2: 'bg-purple-200/30' },
            'purple': { border: 'border-purple-500', bg: 'bg-purple-50', text: 'text-purple-600', badge: 'bg-purple-100/80 text-purple-700 border-purple-200', ping: 'bg-purple-400', section: 'via-purple-50/40', box: 'from-purple-100/60 via-fuchsia-50/50 to-indigo-50/60', blob1: 'bg-fuchsia-200/40', blob2: 'bg-indigo-200/30' },
            'gold': { border: 'border-amber-500', bg: 'bg-amber-50', text: 'text-amber-600', badge: 'bg-amber-100/80 text-amber-700 border-amber-200', ping: 'bg-amber-400', section: 'via-orange-50/40', box: 'from-amber-100/60 via-orange-50/50 to-yellow-50/60', blob1: 'bg-amber-200/40', blob2: 'bg-yellow-200/30' },
            'mint': { border: 'border-teal-500', bg: 'bg-teal-50', text: 'text-teal-600', badge: 'bg-teal-100/80 text-teal-700 border-teal-200', ping: 'bg-teal-400', section: 'via-emerald-50/40', box: 'from-teal-100/60 via-emerald-50/50 to-cyan-50/60', blob1: 'bg-teal-200/40', blob2: 'bg-cyan-200/30' },
            'navy': { border: 'border-slate-500', bg: 'bg-slate-50', text: 'text-slate-600', badge: 'bg-slate-200/80 text-slate-800 border-slate-300', ping: 'bg-slate-400', section: 'via-slate-100/40', box: 'from-slate-200/60 via-slate-100/50 to-blue-100/60', blob1: 'bg-slate-300/40', blob2: 'bg-blue-200/30' }
        };

        // Hero Switcher
        window.switchHeroScent = function(index) {
            if (!PRODUCTS || PRODUCTS.length === 0) return;
            const p = PRODUCTS[index] || PRODUCTS[0];
            const theme = THEME_MAP[p.colorTheme] || THEME_MAP['rose'];
            
            document.getElementById('hero').className = `relative overflow-hidden py-16 lg:py-24 bg-gradient-to-b from-brand-softBg ${theme.section} to-brand-softBg transition-colors duration-500`;
            document.getElementById('heroBlob1').className = `absolute top-1/4 left-10 w-72 h-72 rounded-full blur-3xl pointer-events-none animate-pulse-glow transition-colors duration-500 ${theme.blob1}`;
            document.getElementById('heroBlob2').className = `absolute top-1/3 right-10 w-80 h-80 rounded-full blur-3xl pointer-events-none animate-pulse-glow transition-colors duration-500 ${theme.blob2}`;
            document.getElementById('heroImageBox').className = `relative w-full max-w-md lg:max-w-none h-[440px] sm:h-[480px] flex flex-col items-center justify-between p-6 rounded-3xl bg-gradient-to-tr ${theme.box} backdrop-blur-md border border-white/80 shadow-2xl overflow-hidden transition-all duration-500`;
            
            document.getElementById('heroPing').className = `w-2 h-2 rounded-full animate-ping ${theme.ping}`;
            
            document.getElementById('heroTagline').textContent = p.tagline;
            document.getElementById('heroTagline').className = `font-serif italic text-base ${theme.text}`;
            
            document.getElementById('heroTitle').textContent = p.name;
            
            document.getElementById('heroRefBadge').textContent = `✨ Referência Olfativa: ${p.olfactoryReference || 'Importada'}`;
            document.getElementById('heroRefBadge').className = `inline-block px-3.5 py-1.5 rounded-full text-xs font-bold border ${theme.badge}`;
            
            document.getElementById('heroDescription').textContent = p.description;
            document.getElementById('heroPrice').textContent = `R$ ${(p.price || SINGLE_PRICE).toFixed(2).replace('.', ',')}`;
            document.getElementById('heroImage').src = p.image;
            
            const buyBtn = document.getElementById('heroBuyBtn');
            buyBtn.onclick = () => addToCart(p.id);
            // Re-apply btn background based on the DB value or theme
            buyBtn.className = `w-full sm:w-auto inline-flex items-center justify-center gap-3 ${p.btnBg} px-8 py-4 rounded-full font-medium text-sm transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5`;

            // Update thumbnails
            document.querySelectorAll('.hero-thumb').forEach((btn, i) => {
                const btnTheme = THEME_MAP[PRODUCTS[i].colorTheme] || THEME_MAP['rose'];
                if (i === index) {
                    btn.className = `hero-thumb p-1.5 rounded-xl transition-all border-2 scale-105 ${btnTheme.border} ${btnTheme.bg}`;
                } else {
                    btn.className = `hero-thumb p-1.5 rounded-xl transition-all border-2 border-transparent hover:${btnTheme.border.replace('border-', 'border-opacity-50 border-')}`;
                }
            });
        };

        // Render Product Cards Grid
        function renderProductsGrid(filterCategory = 'all') {
            const grid = document.getElementById('productsGrid');
            if (!grid) return;

            let filtered = PRODUCTS;
            if (filterCategory === 'feminino') {
                filtered = PRODUCTS.filter(p => p.genderTag && p.genderTag.toLowerCase().includes('feminino'));
            } else if (filterCategory === 'masculino-unisex') {
                filtered = PRODUCTS.filter(p => p.genderTag && (p.genderTag.toLowerCase().includes('masculino') || p.genderTag.toLowerCase().includes('unisex')));
            }

            grid.innerHTML = filtered.map(p => `
                <div class="glass-card rounded-3xl p-6 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-xl flex flex-col justify-between border border-rose-100/60 relative group">
                    <div class="absolute top-4 right-4 z-10">
                        <button onclick="openNotesModal('${p.id}')" class="p-2 rounded-full bg-white/80 hover:bg-white text-neutral-600 shadow-xs backdrop-blur-md transition-all" title="Ver Pirâmide Olfativa">
                            <i data-lucide="info" class="w-4 h-4"></i>
                        </button>
                    </div>

                    <div class="w-full h-64 flex items-center justify-center p-4 rounded-2xl bg-gradient-to-b from-pink-50 via-rose-50/50 to-pink-100/40 relative overflow-hidden">
                        <img src="${p.image}" alt="${p.name}" class="max-h-full max-w-full object-contain mix-blend-multiply drop-shadow-xl transition-transform duration-500 group-hover:scale-105">
                    </div>

                    <div class="mt-6 space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-[11px] font-bold uppercase tracking-wider text-rose-600">${p.tagline}</span>
                            <span class="text-xs text-neutral-400">236 mL / 8 fl oz</span>
                        </div>

                        <h3 class="font-serif text-2xl font-semibold text-neutral-900">${p.name}</h3>

                        <div class="inline-block px-3 py-1 rounded-full bg-rose-50 border border-rose-200/80 text-rose-700 text-xs font-bold">
                            ✨ Ref: ${p.olfactoryReference || 'Importada'}
                        </div>

                        <p class="text-xs text-neutral-600 line-clamp-2 leading-relaxed font-light">${p.description}</p>

                        <div class="pt-3 border-t border-neutral-100 flex items-center justify-between">
                            <div>
                                <span class="text-[10px] text-neutral-400 block uppercase font-medium">Preço</span>
                                <span class="font-serif text-xl font-bold text-neutral-900">R$ ${(p.price || SINGLE_PRICE).toFixed(2).replace('.', ',')}</span>
                            </div>

                            <button onclick="addToCart('${p.id}')" class="px-4 py-2.5 rounded-2xl bg-rose-500 hover:bg-rose-600 text-white text-xs font-semibold flex items-center gap-1.5 shadow-sm transition-all">
                                <i data-lucide="plus" class="w-4 h-4"></i>
                                <span>Comprar</span>
                            </button>
                        </div>
                    </div>
                </div>
            `).join('');

            lucide.createIcons();
        }

        window.filterProducts = function(cat, event) {
            if (event && event.target) {
                document.querySelectorAll('#scentFilters .filter-btn').forEach(btn => {
                    btn.classList.remove('bg-neutral-900', 'text-white');
                    btn.classList.add('bg-neutral-100', 'text-neutral-700');
                });
                event.target.classList.remove('bg-neutral-100', 'text-neutral-700');
                event.target.classList.add('bg-neutral-900', 'text-white');
            }
            renderProductsGrid(cat);
        };

        // Olfactory Pyramid Modal
        window.openNotesModal = function(id) {
            const p = PRODUCTS.find(item => item.id === id);
            if (!p) return;

            document.getElementById('modalCategoryBadge').textContent = p.tagline;
            document.getElementById('modalProductName').textContent = p.name;
            document.getElementById('modalTagline').textContent = p.description;
            document.getElementById('modalOlfactoryRef').textContent = `✨ Referência Olfativa: ${p.olfactoryReference || 'Importada'}`;

            document.getElementById('modalTopNotes').textContent = p.notes ? p.notes.top : 'Notas Olfativas Selecionadas';
            document.getElementById('modalHeartNotes').textContent = p.notes ? p.notes.heart : 'Acordes Aromáticos';
            document.getElementById('modalBaseNotes').textContent = p.notes ? p.notes.base : 'Fixação Long-Lasting';
            document.getElementById('modalSensationsText').textContent = p.sensation || 'Toque aveludado e envolvente';

            const btn = document.getElementById('modalAddToCartBtn');
            btn.onclick = function() {
                addToCart(p.id);
                closeNotesModal();
            };

            document.getElementById('notesModal').classList.remove('hidden');
        };

        window.closeNotesModal = function() {
            document.getElementById('notesModal').classList.add('hidden');
        };

        // Custom Kit Builder (Trio R$ <?= $formatPrice($promoComboPrice) ?>)
        function renderBuilderUI() {
            const slotsContainer = document.getElementById('builderSlots');
            const availableContainer = document.getElementById('builderAvailableGrid');
            if (!slotsContainer || !availableContainer) return;

            slotsContainer.innerHTML = [0, 1, 2].map(i => {
                const pid = builderSelection[i];
                if (pid) {
                    const p = PRODUCTS.find(x => x.id === pid);
                    return `
                        <div class="p-4 rounded-2xl border-2 border-rose-300 bg-rose-50/60 text-center flex flex-col items-center justify-between min-h-[160px] relative">
                            <button onclick="removeFromBuilder(${i})" class="absolute top-2 right-2 text-rose-500 font-bold text-xs p-1">✕</button>
                            <img src="${p.image}" class="w-12 h-20 object-contain mix-blend-multiply">
                            <div>
                                <span class="font-bold text-neutral-900 text-xs block">${p.name}</span>
                                <span class="text-[10px] text-rose-600 font-semibold">Slot ${i+1} ✓</span>
                            </div>
                        </div>
                    `;
                } else {
                    return `
                        <div class="p-4 rounded-2xl border-2 border-dashed border-rose-200 bg-rose-50/30 text-center flex flex-col items-center justify-between min-h-[160px]">
                            <span class="text-[10px] font-bold uppercase tracking-widest text-rose-500 bg-rose-100 px-2 py-0.5 rounded-full">Slot ${i+1}</span>
                            <div class="my-auto">
                                <i data-lucide="plus-circle" class="w-8 h-8 text-rose-300 mx-auto mb-1"></i>
                                <span class="text-xs text-neutral-500 font-medium block">Vazio</span>
                            </div>
                        </div>
                    `;
                }
            }).join('');

            availableContainer.innerHTML = PRODUCTS.map(p => `
                <button onclick="addToBuilder('${p.id}')" class="p-2 rounded-xl bg-neutral-50 hover:bg-rose-50 border border-neutral-200 flex flex-col items-center text-center transition-all">
                    <img src="${p.image}" class="w-8 h-12 object-contain mix-blend-multiply">
                    <span class="text-[11px] font-bold text-neutral-800 mt-1 truncate w-full">+ ${p.name}</span>
                </button>
            `).join('');

            const selectedCount = builderSelection.filter(x => x !== null).length;
            document.getElementById('selectedCountBadge').textContent = `${selectedCount}/3 selecionados`;

            const addBundleBtn = document.getElementById('addBundleBtn');
            if (selectedCount === 3) {
                addBundleBtn.disabled = false;
                addBundleBtn.className = 'w-full sm:w-auto px-8 py-3.5 rounded-2xl bg-rose-500 hover:bg-rose-600 text-white font-bold text-sm transition-all shadow-md flex items-center justify-center gap-2 cursor-pointer';
                addBundleBtn.innerHTML = `<i data-lucide="shopping-bag" class="w-4 h-4"></i><span>Adicionar Trio ao Carrinho (R$ <?= $formatPrice($promoComboPrice) ?>)</span>`;
            } else {
                addBundleBtn.disabled = true;
                addBundleBtn.className = 'w-full sm:w-auto px-8 py-3.5 rounded-2xl bg-neutral-200 text-neutral-400 font-medium text-sm transition-all flex items-center justify-center gap-2 cursor-not-allowed';
                addBundleBtn.innerHTML = `<i data-lucide="package-plus" class="w-4 h-4"></i><span>Selecione 3 Frascos (${selectedCount}/3)</span>`;
            }

            lucide.createIcons();
        }

        window.addToBuilder = function(id) {
            const emptyIdx = builderSelection.findIndex(x => x === null);
            if (emptyIdx !== -1) {
                builderSelection[emptyIdx] = id;
                renderBuilderUI();
            } else {
                alert('Você já selecionou os 3 frascos! Para alterar, remova um item clicando no ✕.');
            }
        };

        window.removeFromBuilder = function(index) {
            builderSelection[index] = null;
            renderBuilderUI();
        };

        window.addBundleToCart = function() {
            if (!builderSelection.every(x => x !== null)) return;
            const itemNames = builderSelection.map(id => PRODUCTS.find(p => p.id === id).name).join(' + ');

            cart.push({
                id: 'trio-bundle-' + Date.now(),
                name: 'Combo Trio useLOVELY (3 Frascos)',
                tagline: itemNames,
                price: COMBO_PRICE,
                qty: 1,
                image: PRODUCTS.find(p => p.id === builderSelection[0]).image
            });

            builderSelection = [null, null, null];
            renderBuilderUI();
            updateCartBadge();
            openCart();
        };

        // CORREIOS SHIPPING CALCULATOR FUNCTION
        window.calculateCartShipping = async function() {
            const cepInput = document.getElementById('cartCepInput').value.replace(/\D/g, '');
            const statusMsg = document.getElementById('cartShippingStatus');
            const optionsContainer = document.getElementById('cartShippingOptionsList');

            if (cepInput.length !== 8) {
                statusMsg.textContent = 'CEP inválido.';
                return;
            }

            statusMsg.textContent = 'Calculando...';
            const itemCount = cart.reduce((acc, x) => acc + x.qty, 0);

            try {
                const res = await fetch(`api/calculate_shipping.php?cep=${cepInput}&item_count=${itemCount}`);
                const data = await res.json();

                if (data.status === 'success' && data.options) {
                    availableShippingOptions = data.options;
                    statusMsg.textContent = 'Frete calculado ✓';
                    optionsContainer.classList.remove('hidden');

                    document.getElementById('chkCep').value = cepInput;

                    optionsContainer.innerHTML = availableShippingOptions.map((opt, i) => `
                        <label class="p-2.5 rounded-xl border border-neutral-200 bg-white flex items-center justify-between cursor-pointer hover:border-rose-300 transition-all">
                            <div class="flex items-center gap-2">
                                <input type="radio" name="cartShippingRadio" value="${opt.id}" ${i === 0 ? 'checked' : ''} onchange="selectShippingOption('${opt.id}')" class="text-rose-500 focus:ring-rose-400">
                                <div>
                                    <span class="font-bold text-neutral-800 block text-xs">${opt.name}</span>
                                    <span class="text-[10px] text-neutral-500">${opt.days} dias úteis</span>
                                </div>
                            </div>
                            <span class="font-bold text-neutral-900 text-xs">R$ ${opt.price.toFixed(2).replace('.', ',')}</span>
                        </label>
                    `).join('');

                    selectShippingOption(availableShippingOptions[0].id);
                } else {
                    statusMsg.textContent = data.message || 'Erro ao consultar Correios.';
                }
            } catch (err) {
                statusMsg.textContent = 'Erro ao consultar frete.';
            }
        };

        window.selectShippingOption = function(optionId) {
            const opt = availableShippingOptions.find(o => o.id === optionId);
            if (!opt) return;

            currentShippingCost = opt.price;
            currentShippingMethod = opt.name;

            document.getElementById('cartShippingDisplay').textContent = opt.label;
            renderCartUI();
            renderCheckoutShippingOptions();
        };

        function renderCheckoutShippingOptions() {
            const container = document.getElementById('checkoutShippingOptions');
            if (!container) return;

            if (availableShippingOptions.length === 0) {
                container.innerHTML = `<p class="text-neutral-500 text-[11px]">Digite seu CEP para calcular o frete dos Correios.</p>`;
                return;
            }

            container.innerHTML = availableShippingOptions.map((opt) => `
                <label class="p-3 rounded-xl border border-neutral-200 bg-white flex items-center justify-between cursor-pointer hover:border-rose-400 transition-all">
                    <div class="flex items-center gap-2.5">
                        <input type="radio" name="chkShippingRadio" value="${opt.id}" ${opt.price === currentShippingCost ? 'checked' : ''} onchange="selectShippingOption('${opt.id}')" class="text-rose-500 focus:ring-rose-400">
                        <div>
                            <span class="font-bold text-neutral-800 block text-xs">${opt.name}</span>
                            <span class="text-[11px] text-neutral-500">Prazo estimado: ${opt.days} dias úteis</span>
                        </div>
                    </div>
                    <span class="font-bold text-rose-600 text-xs">R$ ${opt.price.toFixed(2).replace('.', ',')}</span>
                </label>
            `).join('');
        }

        // Perfume Finder Quiz
        function renderQuizStep() {
            const container = document.getElementById('quizStepContainer');
            if (!container) return;

            if (currentQuizStep < QUIZ_QUESTIONS.length) {
                const q = QUIZ_QUESTIONS[currentQuizStep];
                container.innerHTML = `
                    <div class="space-y-6">
                        <div class="flex items-center justify-between text-xs text-rose-200 font-medium border-b border-white/10 pb-3">
                            <span>Pergunta ${currentQuizStep + 1} de ${QUIZ_QUESTIONS.length}</span>
                            <span>Scent Finder</span>
                        </div>

                        <h3 class="font-serif text-2xl sm:text-3xl font-normal text-white">${q.question}</h3>

                        <div class="grid grid-cols-1 gap-3">
                            ${q.options.map((opt) => `
                                <button onclick="answerQuiz('${opt.productId}')" class="p-4 rounded-2xl bg-white/5 hover:bg-white/15 border border-white/10 text-left text-sm text-rose-50 hover:text-white transition-all flex items-center justify-between group">
                                    <span>${opt.label}</span>
                                    <i data-lucide="chevron-right" class="w-4 h-4 text-rose-300 group-hover:translate-x-1 transition-transform"></i>
                                </button>
                            `).join('')}
                        </div>
                    </div>
                `;
            } else {
                const recId = quizAnswers[quizAnswers.length - 1] || 'velvet-bloom';
                const p = PRODUCTS.find(x => x.id === recId) || PRODUCTS[0];

                container.innerHTML = `
                    <div class="text-center space-y-6">
                        <div class="inline-block px-4 py-1.5 rounded-full bg-emerald-500/20 text-emerald-300 text-xs font-bold uppercase tracking-wider border border-emerald-500/30">
                            Seu Match Olfativo Perfeito!
                        </div>

                        <div class="max-w-xs mx-auto h-48 flex items-center justify-center">
                            <img src="${p.image}" class="max-h-full max-w-full object-contain mix-blend-multiply drop-shadow-2xl">
                        </div>

                        <div>
                            <h3 class="font-serif text-3xl font-semibold text-white">${p.name}</h3>
                            <p class="text-xs text-rose-200 font-bold mt-1">✨ Ref: ${p.olfactoryReference || 'Importada'}</p>
                            <p class="text-xs text-rose-100/80 max-w-md mx-auto mt-2 leading-relaxed">${p.description}</p>
                        </div>

                        <div class="pt-4 flex flex-col sm:flex-row items-center justify-center gap-4">
                            <button onclick="addToCart('${p.id}');" class="w-full sm:w-auto px-8 py-3.5 rounded-2xl bg-rose-500 text-white font-semibold text-sm hover:bg-rose-600 transition-all shadow-lg flex items-center justify-center gap-2">
                                <i data-lucide="shopping-bag" class="w-4 h-4"></i>
                                <span>Adicionar ao Carrinho (R$ ${(p.price || SINGLE_PRICE).toFixed(2).replace('.', ',')})</span>
                            </button>
                            <button onclick="resetQuiz()" class="text-xs text-rose-300 hover:text-white underline">
                                Refazer Quiz
                            </button>
                        </div>
                    </div>
                `;
            }
            lucide.createIcons();
        }

        window.answerQuiz = function(productId) {
            quizAnswers.push(productId);
            currentQuizStep++;
            renderQuizStep();
        };

        window.resetQuiz = function() {
            currentQuizStep = 0;
            quizAnswers = [];
            renderQuizStep();
        };

        // Customer Auth Modal
        window.openAuthModal = function(tab = 'login') {
            document.getElementById('authModal').classList.remove('hidden');
            switchAuthTab(tab);
        };
        window.closeAuthModal = function() {
            document.getElementById('authModal').classList.add('hidden');
        };

        window.switchAuthTab = function(tab) {
            const errorMsg = document.getElementById('authErrorMsg');
            errorMsg.classList.add('hidden');

            if (tab === 'login') {
                document.getElementById('customerLoginForm').classList.remove('hidden');
                document.getElementById('customerRegisterForm').classList.add('hidden');
                document.getElementById('tabLoginBtn').className = 'flex-1 py-2.5 text-xs font-bold uppercase tracking-wider border-b-2 border-rose-500 text-rose-600';
                document.getElementById('tabRegisterBtn').className = 'flex-1 py-2.5 text-xs font-bold uppercase tracking-wider border-b-2 border-transparent text-neutral-400 hover:text-neutral-700';
            } else {
                document.getElementById('customerLoginForm').classList.add('hidden');
                document.getElementById('customerRegisterForm').classList.remove('hidden');
                document.getElementById('tabRegisterBtn').className = 'flex-1 py-2.5 text-xs font-bold uppercase tracking-wider border-b-2 border-rose-500 text-rose-600';
                document.getElementById('tabLoginBtn').className = 'flex-1 py-2.5 text-xs font-bold uppercase tracking-wider border-b-2 border-transparent text-neutral-400 hover:text-neutral-700';
            }
        };

        document.getElementById('customerLoginForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const email = document.getElementById('custLoginEmail').value.trim();
            const password = document.getElementById('custLoginPassword').value;
            const errorMsg = document.getElementById('authErrorMsg');

            try {
                const res = await fetch('api/auth_login.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ email, password })
                });

                const result = await res.json();
                if (result.status === 'success') {
                    currentUser = result.user;
                    updateCustomerNavUI();
                    closeAuthModal();
                } else {
                    errorMsg.textContent = result.message || 'Erro ao realizar login.';
                    errorMsg.classList.remove('hidden');
                }
            } catch (err) {
                errorMsg.textContent = 'Erro de conexão MySQL.';
                errorMsg.classList.remove('hidden');
            }
        });

        document.getElementById('customerRegisterForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const email = document.getElementById('custRegEmail').value.trim();
            const password = document.getElementById('custRegPassword').value;
            const name = document.getElementById('custRegName').value;
            const phone = document.getElementById('custRegPhone').value;
            const errorMsg = document.getElementById('authErrorMsg');

            try {
                const res = await fetch('api/auth_register.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ name, email, password, phone })
                });

                const result = await res.json();
                if (result.status === 'success') {
                    currentUser = result.user;
                    updateCustomerNavUI();
                    closeAuthModal();
                    alert('Conta criada com sucesso!');
                } else {
                    errorMsg.textContent = result.message || 'Erro ao criar conta.';
                    errorMsg.classList.remove('hidden');
                }
            } catch (err) {
                errorMsg.textContent = 'Erro ao conectar ao banco de dados.';
                errorMsg.classList.remove('hidden');
            }
        });

        window.logoutCustomer = async function() {
            await fetch('api/auth_logout.php');
            currentUser = null;
            updateCustomerNavUI();
        };

        // ViaCEP Lookup
        window.lookupCep = async function() {
            const cepInput = document.getElementById('chkCep').value.replace(/\D/g, '');
            const msg = document.getElementById('cepStatusMsg');

            if (cepInput.length !== 8) {
                msg.textContent = 'CEP inválido.';
                return;
            }

            msg.textContent = 'Buscando CEP...';
            try {
                const res = await fetch(`https://viacep.com.br/ws/${cepInput}/json/`);
                const data = await res.json();
                if (data.erro) {
                    msg.textContent = 'CEP não encontrado.';
                    return;
                }

                document.getElementById('chkStreet').value = data.logradouro || '';
                document.getElementById('chkNeighborhood').value = data.bairro || '';
                document.getElementById('chkCity').value = data.localidade || '';
                document.getElementById('chkState').value = data.uf || '';
                msg.textContent = 'Endereço encontrado ✓';

                document.getElementById('cartCepInput').value = cepInput;
                calculateCartShipping();
            } catch (err) {
                msg.textContent = 'Erro ao buscar CEP.';
            }
        };

        document.getElementById('chkCep').addEventListener('blur', lookupCep);

        // Cart Drawer
        window.openCart = function() {
            document.getElementById('cartDrawer').classList.remove('hidden');
            renderCartUI();
        };
        window.closeCart = function() {
            document.getElementById('cartDrawer').classList.add('hidden');
        };

        window.addToCart = function(id) {
            const p = PRODUCTS.find(item => item.id === id);
            if (!p) return;

            const existing = cart.find(x => x.id === id);
            if (existing) {
                existing.qty += 1;
            } else {
                cart.push({ ...p, qty: 1 });
            }
            updateCartBadge();
            openCart();
        };

        function updateCartBadge() {
            const count = cart.reduce((acc, x) => acc + x.qty, 0);
            document.getElementById('cartCountBadge').textContent = count;
        }

        function renderCartUI() {
            const list = document.getElementById('cartItemsList');
            if (cart.length === 0) {
                list.innerHTML = `<div class="py-8 text-center text-xs text-neutral-400">Sua sacola está vazia.</div>`;
                document.getElementById('cartSubtotal').textContent = 'R$ 0,00';
                document.getElementById('cartTotal').textContent = 'R$ 0,00';
                document.getElementById('chkFinalTotal').textContent = 'R$ 0,00';
                return;
            }

            list.innerHTML = cart.map(item => `
                <div class="py-3 flex items-center justify-between gap-4 text-xs">
                    <div class="flex items-center gap-3">
                        <img src="${item.image}" class="w-12 h-16 object-contain mix-blend-multiply border rounded-lg">
                        <div>
                            <span class="font-bold text-neutral-900 block">${item.name}</span>
                            <span class="text-neutral-500">R$ ${(item.price || SINGLE_PRICE).toFixed(2).replace('.', ',')}</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <button onclick="changeQty('${item.id}', -1)" class="w-6 h-6 rounded-md bg-neutral-100 text-neutral-700 font-bold">-</button>
                        <span class="font-bold text-neutral-900">${item.qty}</span>
                        <button onclick="changeQty('${item.id}', 1)" class="w-6 h-6 rounded-md bg-neutral-100 text-neutral-700 font-bold">+</button>
                    </div>
                </div>
            `).join('');

            const subtotal = cart.reduce((acc, x) => acc + ((x.price || SINGLE_PRICE) * x.qty), 0);
            const total = subtotal + currentShippingCost;

            document.getElementById('cartSubtotal').textContent = `R$ ${subtotal.toFixed(2).replace('.', ',')}`;
            document.getElementById('cartTotal').textContent = `R$ ${total.toFixed(2).replace('.', ',')}`;
            document.getElementById('chkFinalTotal').textContent = `R$ ${total.toFixed(2).replace('.', ',')}`;
        }

        window.changeQty = function(id, delta) {
            const idx = cart.findIndex(x => x.id === id);
            if (idx !== -1) {
                cart[idx].qty += delta;
                if (cart[idx].qty <= 0) cart.splice(idx, 1);
            }
            updateCartBadge();
            renderCartUI();
        };

        let checkoutSavedAddressesList = [];

        // Checkout Modal & Mercado Pago Preference Generation
        window.openCheckoutModal = async function() {
            if (cart.length === 0) {
                alert('Sua sacola está vazia.');
                return;
            }
            closeCart();
            renderCheckoutShippingOptions();
            document.getElementById('checkoutModal').classList.remove('hidden');

            if (currentUser) {
                try {
                    const res = await fetch('api/get_user_addresses.php');
                    const result = await res.json();
                    const container = document.getElementById('checkoutSavedAddressesContainer');
                    const select = document.getElementById('checkoutSavedAddresses');
                    
                    if (result.status === 'success' && result.data.length > 0) {
                        checkoutSavedAddressesList = result.data;
                        
                        select.innerHTML = '<option value="">Usar um endereço salvo...</option>' + 
                            result.data.map((addr, idx) => 
                                `<option value="${idx}">${addr.street}, ${addr.number} - ${addr.neighborhood}</option>`
                            ).join('');
                        
                        container.classList.remove('hidden');
                    } else {
                        container.classList.add('hidden');
                    }
                } catch (e) {
                    console.error("Erro ao buscar endereços no checkout:", e);
                }
            }
        };

        window.fillSavedAddress = function(indexStr) {
            if (indexStr === "") return;
            const idx = parseInt(indexStr);
            const addr = checkoutSavedAddressesList[idx];
            if (addr) {
                document.getElementById('chkCep').value = addr.cep || '';
                document.getElementById('chkStreet').value = addr.street || '';
                document.getElementById('chkNumber').value = addr.number || '';
                document.getElementById('chkComplement').value = addr.complement || '';
                document.getElementById('chkNeighborhood').value = addr.neighborhood || '';
                document.getElementById('chkCity').value = addr.city || '';
                document.getElementById('chkState').value = addr.state || '';
                lookupCep(); // Recalculate shipping based on new CEP
            }
        };

        window.closeCheckoutModal = function() {
            document.getElementById('checkoutModal').classList.add('hidden');
        };

        document.getElementById('checkoutFullForm').addEventListener('submit', async (e) => {
            e.preventDefault();

            const submitBtn = document.getElementById('submitCheckoutBtn');
            const submitBtnText = document.getElementById('submitCheckoutBtnText');
            const errorMsg = document.getElementById('checkoutErrorMsg');

            errorMsg.classList.add('hidden');
            submitBtn.disabled = true;
            submitBtnText.textContent = "Preparando seu pagamento...";

            const checkoutPayload = {
                customer: {
                    name: document.getElementById('chkName').value.trim(),
                    email: document.getElementById('chkEmail').value.trim(),
                    phone: document.getElementById('chkPhone').value.trim(),
                    cpf: document.getElementById('chkCpf').value.trim()
                },
                address: {
                    cep: document.getElementById('chkCep').value.trim(),
                    street: document.getElementById('chkStreet').value.trim(),
                    number: document.getElementById('chkNumber').value.trim(),
                    complement: document.getElementById('chkComplement').value.trim(),
                    neighborhood: document.getElementById('chkNeighborhood').value.trim(),
                    city: document.getElementById('chkCity').value.trim(),
                    state: document.getElementById('chkState').value.trim()
                },
                shippingCost: currentShippingCost,
                shippingMethod: currentShippingMethod || 'Correios',
                items: cart
            };

            try {
                const res = await fetch('api/mercadopago/create-preference.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(checkoutPayload)
                });

                const result = await res.json();
                if (result.status === 'success' && result.initPoint) {
                    cart = [];
                    updateCartBadge();
                    window.location.href = result.initPoint;
                } else {
                    submitBtn.disabled = false;
                    submitBtnText.textContent = "IR PARA PAGAMENTO";
                    errorMsg.textContent = result.message || 'Não conseguimos iniciar o pagamento. Tente novamente em alguns instantes.';
                    errorMsg.classList.remove('hidden');
                }
            } catch (err) {
                submitBtn.disabled = false;
                submitBtnText.textContent = "IR PARA PAGAMENTO";
                errorMsg.textContent = 'Não conseguimos conectar ao servidor de pagamento. Tente novamente em alguns instantes.';
                errorMsg.classList.remove('hidden');
            }
        });

        // User Orders History Modal
        window.openUserOrdersModal = async function() {
            if (!currentUser) {
                openAuthModal('login');
                return;
            }

            document.getElementById('userOrdersModal').classList.remove('hidden');
            const list = document.getElementById('userOrdersList');
            list.innerHTML = `<div class="py-4 text-center text-xs text-neutral-400">Carregando seus pedidos...</div>`;

            const res = await fetch(`api/get_user_orders.php?email=${encodeURIComponent(currentUser.email)}`);
            const result = await res.json();

            if (result.status === 'success' && result.data.length > 0) {
                list.innerHTML = result.data.map(o => `
                    <div class="p-5 rounded-2xl bg-neutral-50 border border-neutral-200 space-y-3 text-xs">
                        <div class="flex items-center justify-between border-b border-neutral-200 pb-3">
                            <div>
                                <span class="font-bold text-neutral-900 text-sm block">${o.id}</span>
                                <span class="text-[11px] text-neutral-400">${new Date(o.createdAt).toLocaleString()}</span>
                            </div>
                            <span class="px-3 py-1 rounded-full text-[11px] font-bold bg-amber-100 text-amber-800">
                                ${o.status}
                            </span>
                        </div>
                        <div class="text-neutral-600 space-y-1">
                            <p><strong>Entrega:</strong> ${o.address.street}, ${o.address.number} - ${o.address.neighborhood}, ${o.address.city}/${o.address.state} (CEP: ${o.address.cep})</p>
                            <p><strong>Pagamento:</strong> ${o.paymentMethod}</p>
                            <p><strong>Total:</strong> R$ ${o.totalAmount.toFixed(2).replace('.', ',')}</p>
                        </div>
                    </div>
                `).join('');
            } else {
                list.innerHTML = `<div class="py-8 text-center text-xs text-neutral-400">Nenhum pedido realizado ainda.</div>`;
            }
        };

        window.closeUserOrdersModal = function() {
            document.getElementById('userOrdersModal').classList.add('hidden');
        };

        // Mobile Menu toggle
        document.getElementById('mobileMenuBtn').addEventListener('click', () => {
            document.getElementById('mobileMenu').classList.toggle('hidden');
        });

        function closeMobileMenu() {
            document.getElementById('mobileMenu').classList.add('hidden');
        }

        // Load Products from PHP API
        async function loadProducts() {
            try {
                const res = await fetch('api/get_products.php');
                const result = await res.json();
                if (result.status === 'success' && result.data.length > 0) {
                    PRODUCTS = result.data;
                    switchHeroScent(0);
                    renderProductsGrid('all');
                    renderBuilderUI();
                    renderQuizStep();
                }
            } catch (err) {
                console.error("Erro ao carregar produtos:", err);
            }
        }

        // --- FORGOT & RESET PASSWORD LOGIC ---
        window.openForgotPasswordModal = function(e) {
            if (e) e.preventDefault();
            closeAuthModal();
            document.getElementById('forgotPasswordErrorMsg').classList.add('hidden');
            document.getElementById('forgotPasswordSuccessMsg').classList.add('hidden');
            document.getElementById('forgotEmail').value = '';
            document.getElementById('forgotPasswordModal').classList.remove('hidden');
        };

        window.closeForgotPasswordModal = function() {
            document.getElementById('forgotPasswordModal').classList.add('hidden');
        };

        window.backToLoginModal = function(e) {
            if (e) e.preventDefault();
            closeForgotPasswordModal();
            openAuthModal('login');
        };

        document.getElementById('forgotPasswordForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = e.target.querySelector('button[type="submit"]');
            const errorMsg = document.getElementById('forgotPasswordErrorMsg');
            const successMsg = document.getElementById('forgotPasswordSuccessMsg');
            btn.disabled = true;
            btn.textContent = "Enviando...";
            errorMsg.classList.add('hidden');
            successMsg.classList.add('hidden');

            try {
                const res = await fetch('api/forgot_password.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ email: document.getElementById('forgotEmail').value })
                });
                const result = await res.json();
                if (result.status === 'success') {
                    successMsg.textContent = result.message;
                    successMsg.classList.remove('hidden');
                    if(result.debug_link) {
                        console.log("LINK DE RECUPERAÇÃO:", result.debug_link); // Apenas para dev
                    }
                } else {
                    errorMsg.textContent = result.message || 'Erro ao solicitar recuperação.';
                    errorMsg.classList.remove('hidden');
                }
            } catch (err) {
                errorMsg.textContent = 'Erro de comunicação com o servidor.';
                errorMsg.classList.remove('hidden');
            }
            btn.disabled = false;
            btn.textContent = "Enviar Link de Recuperação";
        });

        document.getElementById('resetPasswordForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = e.target.querySelector('button[type="submit"]');
            const errorMsg = document.getElementById('resetPasswordErrorMsg');
            const successMsg = document.getElementById('resetPasswordSuccessMsg');
            btn.disabled = true;
            btn.textContent = "Salvando...";
            errorMsg.classList.add('hidden');
            successMsg.classList.add('hidden');

            try {
                const res = await fetch('api/reset_password.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        token: document.getElementById('resetTokenInput').value,
                        password: document.getElementById('resetNewPassword').value
                    })
                });
                const result = await res.json();
                if (result.status === 'success') {
                    successMsg.textContent = result.message;
                    successMsg.classList.remove('hidden');
                    setTimeout(() => {
                        document.getElementById('resetPasswordModal').classList.add('hidden');
                        openAuthModal('login');
                    }, 2000);
                } else {
                    errorMsg.textContent = result.message || 'Erro ao redefinir senha.';
                    errorMsg.classList.remove('hidden');
                    btn.disabled = false;
                    btn.textContent = "Salvar Nova Senha";
                }
            } catch (err) {
                errorMsg.textContent = 'Erro de comunicação com o servidor.';
                errorMsg.classList.remove('hidden');
                btn.disabled = false;
                btn.textContent = "Salvar Nova Senha";
            }
        });

        // --- MY ACCOUNT LOGIC ---
        window.openMyAccountModal = async function() {
            if (!currentUser) {
                openAuthModal('login');
                return;
            }
            document.getElementById('myAccountModal').classList.remove('hidden');
            switchAccountTab('profile');
            document.getElementById('accountErrorMsg').classList.add('hidden');
            document.getElementById('accountSuccessMsg').classList.add('hidden');

            // Load profile data
            document.getElementById('accName').value = currentUser.name || '';
            document.getElementById('accEmail').value = currentUser.email || '';
            document.getElementById('accPhone').value = currentUser.phone || '';
            document.getElementById('accCpf').value = currentUser.cpf || '';
        };

        window.closeMyAccountModal = function() {
            document.getElementById('myAccountModal').classList.add('hidden');
        };

        window.switchAccountTab = function(tab) {
            const profileBtn = document.getElementById('tabProfileBtn');
            const addressesBtn = document.getElementById('tabAddressesBtn');
            const profileForm = document.getElementById('profileForm');
            const addressesSection = document.getElementById('addressesSection');

            document.getElementById('accountErrorMsg').classList.add('hidden');
            document.getElementById('accountSuccessMsg').classList.add('hidden');

            if (tab === 'profile') {
                profileBtn.className = "flex-1 py-2.5 text-xs font-bold uppercase tracking-wider border-b-2 border-rose-500 text-rose-600";
                addressesBtn.className = "flex-1 py-2.5 text-xs font-bold uppercase tracking-wider border-b-2 border-transparent text-neutral-400 hover:text-neutral-700";
                profileForm.classList.remove('hidden');
                addressesSection.classList.add('hidden');
            } else {
                addressesBtn.className = "flex-1 py-2.5 text-xs font-bold uppercase tracking-wider border-b-2 border-rose-500 text-rose-600";
                profileBtn.className = "flex-1 py-2.5 text-xs font-bold uppercase tracking-wider border-b-2 border-transparent text-neutral-400 hover:text-neutral-700";
                profileForm.classList.add('hidden');
                addressesSection.classList.remove('hidden');
                loadAddresses();
            }
        };

        document.getElementById('profileForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = e.target.querySelector('button[type="submit"]');
            const errorMsg = document.getElementById('accountErrorMsg');
            const successMsg = document.getElementById('accountSuccessMsg');
            btn.disabled = true;
            btn.textContent = "Salvando...";
            errorMsg.classList.add('hidden');
            successMsg.classList.add('hidden');

            const payload = {
                name: document.getElementById('accName').value,
                phone: document.getElementById('accPhone').value,
                cpf: document.getElementById('accCpf').value
            };

            try {
                const res = await fetch('api/update_customer_profile.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });
                const result = await res.json();
                if (result.status === 'success') {
                    successMsg.textContent = 'Perfil atualizado com sucesso!';
                    successMsg.classList.remove('hidden');
                    checkCustomerSession(); // Reload session data
                } else {
                    errorMsg.textContent = result.message || 'Erro ao atualizar perfil.';
                    errorMsg.classList.remove('hidden');
                }
            } catch (err) {
                errorMsg.textContent = 'Erro ao salvar. Verifique sua conexão.';
                errorMsg.classList.remove('hidden');
            }
            btn.disabled = false;
            btn.textContent = "Salvar Alterações";
        });

        async function loadAddresses() {
            const list = document.getElementById('addressesList');
            list.innerHTML = `<div class="text-center text-neutral-400 py-4">Carregando endereços...</div>`;
            try {
                const res = await fetch('api/get_user_addresses.php');
                const result = await res.json();
                if (result.status === 'success') {
                    if (result.data.length === 0) {
                        list.innerHTML = `<div class="text-center text-neutral-500 py-4">Nenhum endereço cadastrado.</div>`;
                    } else {
                        list.innerHTML = result.data.map(addr => `
                            <div class="p-3 border ${addr.is_default == 1 ? 'border-rose-300 bg-rose-50' : 'border-neutral-200'} rounded-xl relative group">
                                ${addr.is_default == 1 ? '<span class="absolute top-3 right-3 text-[10px] font-bold text-rose-600 bg-rose-100 px-2 py-0.5 rounded-full">Padrão</span>' : ''}
                                <div class="text-xs text-neutral-800 pr-16">
                                    <p class="font-bold">${addr.street}, ${addr.number} ${addr.complement ? '- ' + addr.complement : ''}</p>
                                    <p>${addr.neighborhood}, ${addr.city} - ${addr.state}</p>
                                    <p class="text-neutral-500 mt-1">CEP: ${addr.cep}</p>
                                </div>
                                <div class="flex gap-3 mt-3 pt-3 border-t border-neutral-100">
                                    ${addr.is_default == 0 ? `<button onclick="setDefaultAddress(${addr.id})" class="text-blue-600 hover:text-blue-800 font-semibold">Tornar Padrão</button>` : ''}
                                    <button onclick="deleteAddress(${addr.id})" class="text-rose-600 hover:text-rose-800 font-semibold">Excluir</button>
                                </div>
                            </div>
                        `).join('');
                    }
                }
            } catch (err) {
                list.innerHTML = `<div class="text-center text-rose-500 py-4">Erro ao carregar endereços.</div>`;
            }
        }

        window.openAddAddressForm = function() {
            document.getElementById('addAddressForm').reset();
            document.getElementById('addAddressForm').classList.remove('hidden');
        };

        window.closeAddAddressForm = function() {
            document.getElementById('addAddressForm').classList.add('hidden');
        };

        window.fetchAddressForNewAddress = async function() {
            let cep = document.getElementById('addCep').value.replace(/\\D/g, '');
            if (cep.length === 8) {
                try {
                    const response = await fetch(`https://viacep.com.br/ws/${cep}/json/`);
                    const data = await response.json();
                    if (!data.erro) {
                        document.getElementById('addStreet').value = data.logradouro;
                        document.getElementById('addNeighborhood').value = data.bairro;
                        document.getElementById('addCity').value = data.localidade;
                        document.getElementById('addState').value = data.uf;
                        document.getElementById('addNumber').focus();
                    }
                } catch (e) {}
            }
        };

        document.getElementById('addAddressForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = e.target.querySelector('button[type="submit"]');
            btn.disabled = true;
            btn.textContent = "Salvando...";

            const payload = {
                cep: document.getElementById('addCep').value,
                street: document.getElementById('addStreet').value,
                number: document.getElementById('addNumber').value,
                complement: document.getElementById('addComplement').value,
                neighborhood: document.getElementById('addNeighborhood').value,
                city: document.getElementById('addCity').value,
                state: document.getElementById('addState').value
            };

            try {
                const res = await fetch('api/add_user_address.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });
                const result = await res.json();
                if (result.status === 'success') {
                    closeAddAddressForm();
                    loadAddresses();
                } else {
                    alert(result.message || 'Erro ao adicionar endereço');
                }
            } catch (err) {
                alert('Erro de comunicação.');
            }
            btn.disabled = false;
            btn.textContent = "Salvar Endereço";
        });

        window.deleteAddress = async function(id) {
            if(!confirm('Tem certeza que deseja excluir este endereço?')) return;
            try {
                const res = await fetch('api/delete_user_address.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id })
                });
                const result = await res.json();
                if (result.status === 'success') {
                    loadAddresses();
                } else {
                    alert(result.message || 'Erro ao excluir');
                }
            } catch (err) {
                alert('Erro de comunicação.');
            }
        };

        window.setDefaultAddress = async function(id) {
            try {
                const res = await fetch('api/set_default_address.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id })
                });
                const result = await res.json();
                if (result.status === 'success') {
                    loadAddresses();
                } else {
                    alert(result.message || 'Erro ao definir como padrão');
                }
            } catch (err) {
                alert('Erro de comunicação.');
            }
        };


        window.onload = function() {
            // Check for reset_token in URL
            const urlParams = new URLSearchParams(window.location.search);
            const resetToken = urlParams.get('reset_token');
            if (resetToken) {
                document.getElementById('resetTokenInput').value = resetToken;
                document.getElementById('resetPasswordModal').classList.remove('hidden');
                // Remove token from URL
                history.replaceState(null, null, window.location.pathname);
            }

            checkCustomerSession();
            loadProducts();
            lucide.createIcons();
        };
    </script>
</body>
</html>
