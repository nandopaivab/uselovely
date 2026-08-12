        window.CONFIG = {
            singlePrice: 49.9,
            comboPrice: 99.99        };
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

        // Hero Switcher
        window.switchHeroScent = function(index) {
            if (!PRODUCTS || PRODUCTS.length === 0) return;
            const p = PRODUCTS[index] || PRODUCTS[0];
            
            document.getElementById('heroTagline').textContent = p.tagline;
            document.getElementById('heroTitle').textContent = p.name;
            document.getElementById('heroRefBadge').textContent = `✨ Referência Olfativa: ${p.olfactoryReference || 'Importada'}`;
            document.getElementById('heroDescription').textContent = p.description;
            document.getElementById('heroPrice').textContent = `R$ ${(p.price || SINGLE_PRICE).toFixed(2).replace('.', ',')}`;
            document.getElementById('heroImage').src = p.image;
            document.getElementById('heroBuyBtn').onclick = () => addToCart(p.id);

            document.querySelectorAll('.hero-thumb').forEach((btn, i) => {
                if (i === index) {
                    btn.classList.add('border-rose-500', 'scale-105', 'bg-rose-50');
                    btn.classList.remove('border-transparent');
                } else {
                    btn.classList.remove('border-rose-500', 'scale-105', 'bg-rose-50');
                    btn.classList.add('border-transparent');
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

        // Custom Kit Builder (Trio R$ 99,99)
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
                addBundleBtn.innerHTML = `<i data-lucide="shopping-bag" class="w-4 h-4"></i><span>Adicionar Trio ao Carrinho (R$ 99,99)</span>`;
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

        // Checkout Modal & Mercado Pago Preference Generation
        window.openCheckoutModal = function() {
            if (cart.length === 0) {
                alert('Sua sacola está vazia.');
                return;
            }
            closeCart();
            renderCheckoutShippingOptions();
            document.getElementById('checkoutModal').classList.remove('hidden');
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
                            <div class="p-3 border \${addr.is_default == 1 ? 'border-rose-300 bg-rose-50' : 'border-neutral-200'} rounded-xl relative group">
                                \${addr.is_default == 1 ? '<span class="absolute top-3 right-3 text-[10px] font-bold text-rose-600 bg-rose-100 px-2 py-0.5 rounded-full">Padrão</span>' : ''}
                                <div class="text-xs text-neutral-800 pr-16">
                                    <p class="font-bold">\${addr.street}, \${addr.number} \${addr.complement ? '- ' + addr.complement : ''}</p>
                                    <p>\${addr.neighborhood}, \${addr.city} - \${addr.state}</p>
                                    <p class="text-neutral-500 mt-1">CEP: \${addr.cep}</p>
                                </div>
                                <div class="flex gap-3 mt-3 pt-3 border-t border-neutral-100">
                                    \${addr.is_default == 0 ? \`<button onclick="setDefaultAddress(\${addr.id})" class="text-blue-600 hover:text-blue-800 font-semibold">Tornar Padrão</button>\` : ''}
                                    <button onclick="deleteAddress(\${addr.id})" class="text-rose-600 hover:text-rose-800 font-semibold">Excluir</button>
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
                    const response = await fetch(\`https://viacep.com.br/ws/\${cep}/json/\`);
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
