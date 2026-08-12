<?php
// Main useLOVELY Public Store Entrypoint (PHP + Local Database + Mercado Pago)
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
                    colors: {
                        brand: {
                            pink: '#FCE7F0',
                            rose: '#E8A5B8',
                            purple: '#9B72AA',
                            gold: '#E3A857',
                            mint: '#6FB3B0',
                            navy: '#3B4861',
                            dark: '#1C1917',
                        }
                    },
                    fontFamily: {
                        serif: ['"Cormorant Garamond"', 'Georgia', 'serif'],
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    boxShadow: {
                        'glow-rose': '0 20px 40px -15px rgba(232, 165, 184, 0.35)',
                        'glow-purple': '0 20px 40px -15px rgba(155, 114, 170, 0.35)',
                        'glow-gold': '0 20px 40px -15px rgba(227, 168, 87, 0.35)',
                        'glow-mint': '0 20px 40px -15px rgba(111, 179, 176, 0.35)',
                        'glow-navy': '0 20px 40px -15px rgba(59, 72, 97, 0.35)',
                    }
                }
            }
        }
    </script>
    <style>
        .glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.6);
        }
        .hero-glow {
            filter: blur(90px);
            transition: all 0.7s cubic-bezier(0.4, 0, 0.2, 1);
        }
    </style>
</head>
<body class="bg-[#FAF8F5] text-neutral-800 font-sans antialiased overflow-x-hidden selection:bg-rose-200 selection:text-rose-900">

    <!-- Top Announcement Bar -->
    <div class="bg-neutral-900 text-white py-2.5 px-4 text-center text-xs font-medium tracking-wide flex items-center justify-center gap-2 shadow-sm">
        <span class="inline-block w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
        <span><strong>Super Oferta:</strong> 1 Body Splash por <strong>R$ 49,90</strong> ou leve <strong>3 por R$ 99,99 + Frete Grátis!</strong></span>
    </div>

    <!-- Navigation Header -->
    <header class="sticky top-0 z-40 bg-white/80 backdrop-blur-md border-b border-neutral-100 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <div class="flex items-center gap-8">
                <a href="#" class="font-serif text-2xl sm:text-3xl font-bold tracking-tight text-neutral-900 flex items-center gap-1.5 group">
                    <span>use</span>
                    <span class="text-rose-500 font-normal italic tracking-normal transition-transform group-hover:scale-105 inline-block">LOVELY</span>
                </a>

                <nav class="hidden md:flex items-center gap-6 text-xs font-semibold uppercase tracking-wider text-neutral-600">
                    <a href="#hero" class="hover:text-rose-600 transition-colors">Destaque</a>
                    <a href="#fragrances" class="hover:text-rose-600 transition-colors">Coleção</a>
                    <a href="#builder" class="hover:text-rose-600 transition-colors flex items-center gap-1 text-rose-600 font-bold"><i data-lucide="sparkles" class="w-3.5 h-3.5"></i> Monte seu Trio</a>
                    <a href="#quiz" class="hover:text-rose-600 transition-colors">Perfume Finder</a>
                </nav>
            </div>

            <div class="flex items-center gap-4">
                <button onclick="toggleQuizModal()" class="hidden sm:flex items-center gap-2 px-4 py-2 rounded-full bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-semibold transition-all border border-rose-100">
                    <i data-lucide="sparkles" class="w-3.5 h-3.5"></i>
                    <span>Descubra seu Aroma</span>
                </button>

                <button onclick="openCart()" class="relative p-2.5 rounded-full hover:bg-neutral-100 transition-colors" aria-label="Sacola de Compras">
                    <i data-lucide="shopping-bag" class="w-5 h-5 text-neutral-800"></i>
                    <span id="cartCountBadge" class="absolute top-1 right-1 bg-rose-500 text-white text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center shadow-xs">0</span>
                </button>
            </div>
        </div>
    </header>

    <!-- Main Container -->
    <main>
        <!-- Hero Section -->
        <section id="hero" class="relative min-h-[85vh] flex items-center justify-center py-12 lg:py-20 overflow-hidden">
            <div id="heroGlow" class="hero-glow absolute w-[450px] h-[450px] rounded-full bg-pink-200/60 top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 pointer-events-none"></div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                    
                    <div class="lg:col-span-5 space-y-6 text-center lg:text-left">
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/80 border border-neutral-200 shadow-xs text-xs font-semibold text-neutral-700">
                            <i data-lucide="sparkles" class="w-3.5 h-3.5 text-amber-500"></i>
                            <span id="heroTagline">Floral Delicado & Aveludado</span>
                        </div>

                        <h1 id="heroTitle" class="font-serif text-4xl sm:text-5xl lg:text-6xl font-semibold text-neutral-900 leading-tight">
                            Velvet Bloom
                        </h1>

                        <p id="heroDescription" class="text-sm sm:text-base text-neutral-600 font-light leading-relaxed max-w-xl mx-auto lg:mx-0">
                            Uma explosão romântica de pétalas de rosa aveludadas entrelaçadas com notas doces de baunilha em flor.
                        </p>

                        <div class="flex items-baseline justify-center lg:justify-start gap-3">
                            <span class="text-xs uppercase font-medium text-neutral-400">Preço Especial:</span>
                            <span id="heroPrice" class="font-serif text-3xl font-bold text-neutral-900">R$ 49,90</span>
                            <span class="text-xs text-rose-600 font-bold bg-rose-50 px-2.5 py-1 rounded-full border border-rose-100">Leve 3 por R$ 99,99</span>
                        </div>

                        <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4 pt-2">
                            <button id="heroBuyBtn" onclick="addToCart(PRODUCTS[0].id)" class="w-full sm:w-auto px-8 py-4 rounded-full bg-rose-500 hover:bg-rose-600 text-white font-semibold text-sm shadow-lg transition-all hover:scale-105 flex items-center justify-center gap-2">
                                <i data-lucide="shopping-bag" class="w-4 h-4"></i>
                                <span>Adicionar por R$ 49,90</span>
                            </button>

                            <a href="#builder" class="w-full sm:w-auto px-8 py-4 rounded-full bg-white hover:bg-neutral-50 text-neutral-800 border border-neutral-200 font-semibold text-sm shadow-xs transition-all text-center">
                                Montar Trio por R$ 99,99
                            </a>
                        </div>
                    </div>

                    <!-- Hero Render Image -->
                    <div class="lg:col-span-7 flex flex-col items-center justify-center">
                        <div class="relative w-full max-w-md h-[400px] sm:h-[460px] flex items-center justify-center p-6 rounded-3xl bg-gradient-to-br from-pink-100 via-rose-50 to-pink-200/90 shadow-2xl border border-pink-200/60 overflow-hidden group">
                            <div id="heroImageBgGlow" class="absolute inset-0 bg-rose-200/50 rounded-full blur-2xl pointer-events-none transition-all duration-700"></div>
                            <img id="heroImage" src="assets/images/velvet_bloom.jpg" alt="Velvet Bloom" class="relative z-10 max-h-full max-w-full object-contain mix-blend-multiply drop-shadow-2xl transition-all duration-500 group-hover:scale-105">
                        </div>

                        <!-- Scent Selector Miniatures -->
                        <div class="mt-6 flex items-center justify-center gap-3 flex-wrap">
                            <button onclick="switchHeroScent(0)" class="scent-thumb-btn active border-2 border-rose-500 p-1.5 rounded-2xl bg-white shadow-md transition-all scale-110" title="Velvet Bloom">
                                <img src="assets/images/velvet_bloom.jpg" class="w-10 h-10 object-contain mix-blend-multiply">
                            </button>
                            <button onclick="switchHeroScent(1)" class="scent-thumb-btn border-2 border-transparent p-1.5 rounded-2xl bg-white shadow-xs hover:border-purple-300 transition-all" title="Purple Kiss">
                                <img src="assets/images/purple_kiss.jpg" class="w-10 h-10 object-contain mix-blend-multiply">
                            </button>
                            <button onclick="switchHeroScent(2)" class="scent-thumb-btn border-2 border-transparent p-1.5 rounded-2xl bg-white shadow-xs hover:border-amber-300 transition-all" title="Golden Glow">
                                <img src="assets/images/golden_glow.jpg" class="w-10 h-10 object-contain mix-blend-multiply">
                            </button>
                            <button onclick="switchHeroScent(3)" class="scent-thumb-btn border-2 border-transparent p-1.5 rounded-2xl bg-white shadow-xs hover:border-teal-300 transition-all" title="Fresh Muse">
                                <img src="assets/images/fresh_muse.jpg" class="w-10 h-10 object-contain mix-blend-multiply">
                            </button>
                            <button onclick="switchHeroScent(4)" class="scent-thumb-btn border-2 border-transparent p-1.5 rounded-2xl bg-white shadow-xs hover:border-slate-300 transition-all" title="Midnight Pulse">
                                <img src="assets/images/midnight_pulse.jpg" class="w-10 h-10 object-contain mix-blend-multiply">
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- Products Collection Grid -->
        <section id="fragrances" class="py-20 bg-white border-t border-neutral-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <div class="text-center max-w-2xl mx-auto space-y-3">
                    <span class="text-xs uppercase font-bold tracking-widest text-rose-500">Coleção de Fragrâncias</span>
                    <h2 class="font-serif text-3xl sm:text-4xl font-semibold text-neutral-900">Nossa Linha Signature</h2>
                    <p class="text-sm text-neutral-500 font-light">Body Splashes de 236 mL com alta fixação e acordes marcantes. Leve individualmente por R$ 49,90 ou 3 por R$ 99,99.</p>
                </div>

                <!-- Filter Tabs -->
                <div class="mt-10 flex flex-wrap items-center justify-center gap-2 sm:gap-3" id="scentFilters">
                    <button onclick="filterProducts('all', event)" class="filter-btn active px-5 py-2.5 rounded-full text-xs sm:text-sm font-semibold transition-all bg-neutral-900 text-white shadow-xs">Todos (5)</button>
                    <button onclick="filterProducts('feminino', event)" class="filter-btn px-5 py-2.5 rounded-full text-xs sm:text-sm font-medium transition-all bg-neutral-100 text-neutral-700 hover:bg-neutral-200">Femininos</button>
                    <button onclick="filterProducts('masculino-unisex', event)" class="filter-btn px-5 py-2.5 rounded-full text-xs sm:text-sm font-medium transition-all bg-neutral-100 text-neutral-700 hover:bg-neutral-200">Masculinos & Unisex</button>
                    <button onclick="filterProducts('floral', event)" class="filter-btn px-5 py-2.5 rounded-full text-xs sm:text-sm font-medium transition-all bg-neutral-100 text-neutral-700 hover:bg-neutral-200">Florais</button>
                    <button onclick="filterProducts('doce', event)" class="filter-btn px-5 py-2.5 rounded-full text-xs sm:text-sm font-medium transition-all bg-neutral-100 text-neutral-700 hover:bg-neutral-200">Adocicados</button>
                    <button onclick="filterProducts('ensolarado', event)" class="filter-btn px-5 py-2.5 rounded-full text-xs sm:text-sm font-medium transition-all bg-neutral-100 text-neutral-700 hover:bg-neutral-200">Ensolarados</button>
                    <button onclick="filterProducts('fresco', event)" class="filter-btn px-5 py-2.5 rounded-full text-xs sm:text-sm font-medium transition-all bg-neutral-100 text-neutral-700 hover:bg-neutral-200">Refrescantes</button>
                </div>

                <!-- Products Grid Container -->
                <div id="productsGrid" class="mt-12 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Injected dynamically via PHP REST API -->
                </div>

            </div>
        </section>

        <!-- Custom Kit Builder Section (Monte Seu Trio R$ 99,99) -->
        <section id="builder" class="py-20 bg-gradient-to-b from-[#FAF8F5] to-rose-50/40">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <div class="text-center max-w-2xl mx-auto space-y-3">
                    <span class="text-xs uppercase font-bold tracking-widest text-rose-600 bg-rose-100 px-3 py-1 rounded-full">Oferta Promocional</span>
                    <h2 class="font-serif text-3xl sm:text-4xl font-semibold text-neutral-900">Monte Seu Trio por R$ 99,99</h2>
                    <p class="text-sm text-neutral-600 font-light">Escolha 3 fragrâncias da sua preferência. Economize <strong>R$ 49,71</strong> com <strong>Frete Grátis</strong> e Caixa de Presente inclusa.</p>
                </div>

                <div class="mt-12 grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                    <!-- Selection Slots Container -->
                    <div class="lg:col-span-8 bg-white p-6 sm:p-8 rounded-3xl border border-neutral-200 shadow-sm space-y-6">
                        <h3 class="font-serif text-xl font-bold text-neutral-900 flex items-center gap-2">
                            <span>Seus 3 Frascos Selecionados</span>
                            <span id="selectedCountBadge" class="text-xs font-sans font-semibold bg-rose-100 text-rose-700 px-2.5 py-0.5 rounded-full">0/3 selecionados</span>
                        </h3>

                        <div class="grid grid-cols-3 gap-4" id="builderSlots">
                            <!-- Injected dynamically -->
                        </div>

                        <div class="pt-4 border-t border-neutral-100">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-neutral-500 mb-4">Clique para adicionar ao seu Trio:</h4>
                            <div class="grid grid-cols-2 sm:grid-cols-5 gap-3" id="builderAvailableGrid">
                                <!-- Injected dynamically -->
                            </div>
                        </div>
                    </div>

                    <!-- Trio Bundle Summary Box -->
                    <div class="lg:col-span-4 bg-neutral-900 text-white p-6 sm:p-8 rounded-3xl shadow-xl space-y-6">
                        <div>
                            <span class="text-xs font-bold uppercase tracking-widest text-rose-400">Resumo da Promoção</span>
                            <h3 class="font-serif text-3xl font-semibold mt-1">Trio useLOVELY</h3>
                        </div>

                        <div class="space-y-3 text-xs border-y border-neutral-800 py-4">
                            <div class="flex justify-between text-neutral-400">
                                <span>Preço unitário individual (3x R$ 49,90):</span>
                                <span class="line-through">R$ 149,70</span>
                            </div>
                            <div class="flex justify-between text-emerald-400 font-semibold">
                                <span>Desconto do Kit Trio:</span>
                                <span>- R$ 49,71</span>
                            </div>
                            <div class="flex justify-between text-neutral-300">
                                <span>Frete:</span>
                                <span class="text-emerald-400 font-bold">GRÁTIS</span>
                            </div>
                            <div class="flex justify-between text-neutral-300">
                                <span>Caixa Gift Box:</span>
                                <span class="text-rose-400 font-semibold">INCLUSA</span>
                            </div>
                        </div>

                        <div>
                            <span class="text-xs text-neutral-400 block mb-1">Valor do Combo:</span>
                            <span class="font-serif text-4xl font-bold text-white">R$ 99,99</span>
                        </div>

                        <button id="addBundleBtn" onclick="addBundleToCart()" disabled class="w-full py-4 rounded-2xl bg-rose-500 hover:bg-rose-600 disabled:bg-neutral-800 disabled:text-neutral-500 text-white font-semibold text-sm transition-all shadow-md flex items-center justify-center gap-2">
                            <i data-lucide="shopping-bag" class="w-4 h-4"></i>
                            <span>Adicionar Trio ao Carrinho</span>
                        </button>
                    </div>
                </div>

            </div>
        </section>

    </main>

    <!-- Footer -->
    <footer class="bg-neutral-900 text-white py-12 border-t border-neutral-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-xs text-neutral-400 space-y-4">
            <p>© 2026 useLOVELY Cosmetics • Todos os direitos reservados.</p>
            <p>THREE COMÉRCIO E SERVIÇOS LTDA • CNPJ 21.610.150/0001-97</p>
        </div>
    </footer>

    <!-- Firebase / PHP Bridge Engine -->
    <script type="module">
        let PRODUCTS = [];
        let selectedBundle = [];

        // Global function bindings
        window.switchHeroScent = function(idx) {
            if (!PRODUCTS[idx]) return;
            const p = PRODUCTS[idx];
            document.getElementById('heroTitle').textContent = p.name;
            document.getElementById('heroTagline').textContent = p.tagline;
            document.getElementById('heroDescription').textContent = p.description;
            document.getElementById('heroPrice').textContent = `R$ ${p.price.toFixed(2).replace('.', ',')}`;
            document.getElementById('heroImage').src = p.image;
        };

        window.filterProducts = function(cat, evt) {
            const grid = document.getElementById('productsGrid');
            grid.innerHTML = '';

            const filtered = cat === 'all' 
                ? PRODUCTS 
                : PRODUCTS.filter(p => p.category === cat || p.genderGroup === cat);

            filtered.forEach(p => {
                const card = document.createElement('div');
                card.className = `glass-card rounded-3xl p-6 transition-all duration-300 hover:-translate-y-1.5 hover:shadow-2xl flex flex-col justify-between border border-rose-100/70 relative group`;
                card.innerHTML = `
                    <div class="absolute top-4 left-4 z-10">
                        <span class="text-[10px] font-bold px-2.5 py-1 rounded-full border border-black/5 shadow-2xs ${p.genderBadge}">
                            ${p.genderTag}
                        </span>
                    </div>

                    <div class="w-full h-72 flex items-center justify-center p-4 rounded-2xl bg-gradient-to-b ${p.bgGradient} relative overflow-hidden">
                        <img src="${p.image}" alt="${p.name}" class="h-60 max-w-full object-contain mix-blend-multiply drop-shadow-xl transition-transform duration-500 group-hover:scale-108">
                    </div>

                    <div class="mt-6 space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-[11px] font-bold uppercase tracking-wider ${p.accentText}">${p.tagline}</span>
                            <span class="text-xs font-medium text-neutral-400">${p.volume}</span>
                        </div>

                        <h3 class="font-serif text-2xl font-semibold text-neutral-900">${p.name}</h3>
                        <p class="text-xs text-neutral-600 line-clamp-2 leading-relaxed font-light">${p.description}</p>

                        <div class="pt-3 border-t border-neutral-100 flex items-center justify-between">
                            <div>
                                <span class="text-[10px] text-neutral-400 block uppercase font-medium">Preço</span>
                                <span class="font-serif text-xl font-bold text-neutral-900">R$ ${p.price.toFixed(2).replace('.', ',')}</span>
                            </div>

                            <button onclick="addToCart('${p.id}')" class="px-4 py-2.5 rounded-2xl ${p.btnBg} text-xs font-semibold flex items-center gap-1.5 shadow-sm transition-all hover:scale-105">
                                <i data-lucide="plus" class="w-4 h-4"></i>
                                <span>Comprar</span>
                            </button>
                        </div>
                    </div>
                `;
                grid.appendChild(card);
            });
            lucide.createIcons();
        };

        window.addToCart = function(id) {
            alert(`Produto adicionado à sacola de compras!`);
        };

        window.addBundleToCart = function() {
            alert(`Trio Promocional (3 frascos) por R$ 99,99 adicionado com sucesso!`);
        };

        // Fetch products from PHP Local API
        async function loadProducts() {
            try {
                const res = await fetch('api/get_products.php');
                const result = await res.json();
                if (result.status === 'success' && result.data.length > 0) {
                    PRODUCTS = result.data;
                    window.switchHeroScent(0);
                    window.filterProducts('all');
                    renderBundlePicker();
                }
            } catch (err) {
                console.error("Erro ao carregar produtos da API PHP:", err);
            }
        }

        function renderBundlePicker() {
            const slots = document.getElementById('builderSlots');
            slots.innerHTML = [0, 1, 2].map(i => `
                <div class="h-32 rounded-2xl border-2 border-dashed border-rose-200 flex flex-col items-center justify-center p-2 bg-rose-50/20 text-center">
                    <span class="text-[11px] font-semibold text-rose-500">Frasco ${i+1}</span>
                    <span class="text-[10px] text-neutral-400">Selecione abaixo</span>
                </div>
            `).join('');

            const available = document.getElementById('builderAvailableGrid');
            available.innerHTML = PRODUCTS.map(p => `
                <button onclick="addToCart('${p.id}')" class="p-2.5 rounded-xl border border-neutral-200 hover:border-rose-400 bg-white text-center text-xs font-semibold hover:shadow-md transition-all">
                    <img src="${p.image}" class="w-12 h-12 object-contain mix-blend-multiply mx-auto mb-1">
                    <span class="block text-[11px] truncate">${p.name}</span>
                </button>
            `).join('');
        }

        window.onload = function() {
            loadProducts();
            lucide.createIcons();
        };
    </script>
</body>
</html>
