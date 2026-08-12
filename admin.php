<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>useLOVELY | Painel Administrativo (PHP + Banco Local)</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Lucide Icons CDN -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        serif: ['"Cormorant Garamond"', 'Georgia', 'serif'],
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    
    <style>
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(226, 232, 240, 0.8);
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 font-sans min-h-screen flex flex-col antialiased">

    <!-- Login View (Exclusively Login - Firebase Auth for Access ONLY) -->
    <div id="loginView" class="min-h-screen flex items-center justify-center p-4 bg-gradient-to-br from-rose-50 via-slate-100 to-purple-50">
        <div class="max-w-md w-full glass-card p-8 rounded-3xl shadow-2xl space-y-6">
            
            <div class="text-center space-y-2">
                <div class="w-12 h-12 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center mx-auto shadow-sm">
                    <i data-lucide="lock" class="w-6 h-6"></i>
                </div>
                <h1 class="font-serif text-3xl font-bold text-slate-900">useLOVELY Admin</h1>
                <p class="text-xs text-slate-500">Login Firebase • Banco de Dados Local PHP</p>
            </div>

            <div id="loginErrorMsg" class="hidden p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-xs font-semibold text-rose-600"></div>

            <form id="loginForm" class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">E-mail Administrativo</label>
                    <div class="relative">
                        <i data-lucide="mail" class="w-4 h-4 text-slate-400 absolute left-3.5 top-3.5"></i>
                        <input type="email" id="loginEmail" required placeholder="admin@uselovely.com.br" class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-300 text-xs text-slate-900 focus:outline-none focus:border-rose-500 focus:ring-1 focus:ring-rose-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Senha de Acesso</label>
                    <div class="relative">
                        <i data-lucide="key" class="w-4 h-4 text-slate-400 absolute left-3.5 top-3.5"></i>
                        <input type="password" id="loginPassword" required placeholder="••••••••" class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-300 text-xs text-slate-900 focus:outline-none focus:border-rose-500 focus:ring-1 focus:ring-rose-500">
                    </div>
                </div>

                <button type="submit" id="loginSubmitBtn" class="w-full py-3 bg-slate-900 hover:bg-slate-800 text-white font-semibold text-xs rounded-xl transition-all shadow-md flex items-center justify-center gap-2">
                    <i data-lucide="log-in" class="w-4 h-4"></i>
                    <span>Entrar no Painel</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Admin Dashboard View (Protected View) -->
    <div id="dashboardView" class="hidden min-h-screen flex flex-col">
        <!-- Top Nav -->
        <header class="bg-white border-b border-slate-200 sticky top-0 z-30">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="font-serif text-xl font-bold text-slate-900">useLOVELY <span class="text-xs uppercase font-sans tracking-widest text-rose-600 bg-rose-50 px-2 py-0.5 rounded-full border border-rose-100 ml-1">Painel ADM (PHP + Banco Local)</span></span>
                </div>
                
                <div class="flex items-center gap-4">
                    <span id="userEmailBadge" class="text-xs text-slate-600 font-medium"></span>
                    <button id="logoutBtn" class="px-3.5 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold flex items-center gap-1.5 transition-all">
                        <i data-lucide="log-out" class="w-3.5 h-3.5"></i>
                        <span>Sair</span>
                    </button>
                </div>
            </div>
        </header>

        <!-- Main Dashboard Content -->
        <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
            
            <!-- Quick Stats -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div class="p-5 rounded-2xl bg-white border border-slate-200 shadow-xs flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center">
                        <i data-lucide="package" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <span class="text-xs text-slate-500 font-medium">Fragrâncias no Banco</span>
                        <h3 id="statTotalProducts" class="font-serif text-2xl font-bold text-slate-900">5</h3>
                    </div>
                </div>

                <div class="p-5 rounded-2xl bg-white border border-slate-200 shadow-xs flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center">
                        <i data-lucide="tag" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <span class="text-xs text-slate-500 font-medium">Preço Unitário / Promo Trio</span>
                        <h3 id="statPrices" class="font-serif text-2xl font-bold text-slate-900">R$ 49,90 / R$ 99,99</h3>
                    </div>
                </div>

                <div class="p-5 rounded-2xl bg-white border border-slate-200 shadow-xs flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center">
                        <i data-lucide="database" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <span class="text-xs text-slate-500 font-medium">Banco de Dados Local</span>
                        <h3 id="statDatabase" class="text-sm font-semibold text-emerald-600 flex items-center gap-1">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span> PHP PDO Conectado
                        </h3>
                    </div>
                </div>
            </div>

            <!-- Mercado Pago API Configuration Card -->
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-lg">
                            MP
                        </div>
                        <div>
                            <h2 class="font-serif text-xl font-bold text-slate-900">Configuração Mercado Pago (Banco Local)</h2>
                            <p class="text-xs text-slate-500">Salva suas chaves diretamente no banco de dados local via PHP</p>
                        </div>
                    </div>
                    <span id="mpStatusBadge" class="text-[11px] font-semibold px-3 py-1 rounded-full bg-slate-100 text-slate-600">Não configurado</span>
                </div>

                <form id="mpConfigForm" class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Public Key (Chave Pública)</label>
                        <input type="text" id="mpPublicKey" placeholder="Ex: TEST-xxxxxx-xxxxxx ou APP_USR-xxxxxx" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:border-blue-500 font-mono text-[11px]">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Access Token (Chave Privada - opcional)</label>
                        <input type="password" id="mpAccessToken" placeholder="Ex: TEST-xxxxxx-xxxxxx ou APP_USR-xxxxxx" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:border-blue-500 font-mono text-[11px]">
                    </div>
                    <div class="sm:col-span-2 flex justify-end">
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs transition-all shadow-md flex items-center gap-2">
                            <i data-lucide="check-circle" class="w-4 h-4"></i>
                            <span>Salvar no Banco Local</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Customer Orders Management Section -->
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden space-y-4">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h2 class="font-serif text-2xl font-bold text-slate-900">Gerenciador de Pedidos dos Clientes</h2>
                        <p class="text-xs text-slate-500">Acompanhe e atualize os pedidos realizados no e-commerce</p>
                    </div>
                    <button onclick="fetchAdminOrders()" class="px-3.5 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs transition-all flex items-center gap-1.5">
                        <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i>
                        <span>Atualizar Pedidos</span>
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                <th class="py-3.5 px-6">Pedido / Data</th>
                                <th class="py-3.5 px-4">Cliente / Contato</th>
                                <th class="py-3.5 px-4">Endereço de Entrega</th>
                                <th class="py-3.5 px-4">Pagamento & Total</th>
                                <th class="py-3.5 px-4">Status do Envio</th>
                            </tr>
                        </thead>
                        <tbody id="adminOrdersTable" class="divide-y divide-slate-100 text-xs">
                            <!-- Injected dynamically via JS -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Products Management Section -->
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h2 class="font-serif text-2xl font-bold text-slate-900">Gerenciador de Fragrâncias e Preços</h2>
                        <p class="text-xs text-slate-500">Altere no banco de dados local via PHP em tempo real</p>
                    </div>
                    <button id="seedLocalDbBtn" class="px-4 py-2 bg-rose-50 hover:bg-rose-100 text-rose-700 font-semibold text-xs rounded-xl transition-all border border-rose-200 flex items-center gap-2">
                        <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i>
                        <span>Restaurar 5 Produtos no Banco Local</span>
                    </button>
                </div>

                <!-- Products Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                <th class="py-3.5 px-6">Produto</th>
                                <th class="py-3.5 px-4">Gênero / Categoria</th>
                                <th class="py-3.5 px-4">Preço Unitário</th>
                                <th class="py-3.5 px-4">Preço Trio Promo</th>
                                <th class="py-3.5 px-6 text-right">Ações</th>
                            </tr>
                        </thead>
                        <tbody id="adminProductsTable" class="divide-y divide-slate-100 text-xs">
                            <!-- Injected dynamically via PHP API -->
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
    </div>

    <!-- Edit Product Modal -->
    <div id="editProductModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
        <div class="bg-white rounded-3xl max-w-2xl w-full p-6 sm:p-8 relative shadow-2xl max-h-[90vh] overflow-y-auto">
            <button id="closeEditModalBtn" class="absolute top-4 right-4 p-2 text-slate-400 hover:text-slate-700">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>

            <h3 class="font-serif text-2xl font-bold text-slate-900 mb-1">Editar Fragrância (Banco Local)</h3>
            <p class="text-xs text-slate-500 mb-6">As alterações são salvas diretamente no seu banco de dados local em PHP.</p>

            <form id="editProductForm" class="space-y-4 text-xs">
                <input type="hidden" id="editProductId">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Nome da Fragrância</label>
                        <input type="text" id="editProductName" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:border-rose-500">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Subtítulo / Tagline</label>
                        <input type="text" id="editProductTagline" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:border-rose-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Preço Unitário (R$)</label>
                        <input type="number" step="0.01" id="editProductPrice" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:border-rose-500">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Classificação de Gênero</label>
                        <select id="editProductGenderTag" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:border-rose-500 bg-white">
                            <option value="Feminino">Feminino</option>
                            <option value="Feminino & Envolvente">Feminino & Envolvente</option>
                            <option value="Unisex / Compartilhável">Unisex / Compartilhável</option>
                            <option value="Masculino & Unisex">Masculino & Unisex</option>
                            <option value="Masculino & Unisex Noturno">Masculino & Unisex Noturno</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Caminho / URL da Imagem</label>
                    <input type="text" id="editProductImage" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:border-rose-500 font-mono text-[11px]">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Descrição</label>
                    <textarea id="editProductDescription" rows="2" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:border-rose-500"></textarea>
                </div>

                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-3">
                    <span class="font-bold text-slate-800 text-xs block">Pirâmide Olfativa</span>
                    <div>
                        <label class="block text-[11px] text-slate-600 font-medium mb-1">Notas de Topo (Saída)</label>
                        <input type="text" id="editNotesTop" required class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:border-rose-500">
                    </div>
                    <div>
                        <label class="block text-[11px] text-slate-600 font-medium mb-1">Notas de Coração (Corpo)</label>
                        <input type="text" id="editNotesHeart" required class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:border-rose-500">
                    </div>
                    <div>
                        <label class="block text-[11px] text-slate-600 font-medium mb-1">Notas de Fundo (Fixação)</label>
                        <input type="text" id="editNotesBase" required class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:border-rose-500">
                    </div>
                </div>

                <div class="pt-2 flex justify-end gap-3">
                    <button type="button" id="cancelEditBtn" class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold">
                        Cancelar
                    </button>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-semibold flex items-center gap-2 shadow-md">
                        <i data-lucide="save" class="w-4 h-4"></i>
                        <span>Salvar no Banco Local</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- 100% PHP & MySQL Engine (Zero Firebase Dependency) -->
    <script>
        let loadedProducts = [];
        let currentUser = null;

        // Check PHP session on load
        async function checkAdminSession() {
            try {
                const res = await fetch('api/auth_check.php');
                const result = await res.json();
                if (result.loggedIn && result.user) {
                    currentUser = result.user;
                    document.getElementById('loginView').classList.add('hidden');
                    document.getElementById('dashboardView').classList.remove('hidden');
                    document.getElementById('userEmailBadge').textContent = currentUser.email;
                    fetchLocalData();
                } else {
                    document.getElementById('loginView').classList.remove('hidden');
                    document.getElementById('dashboardView').classList.add('hidden');
                }
            } catch (e) {
                console.error("Erro ao verificar sessão PHP:", e);
                document.getElementById('loginView').classList.remove('hidden');
                document.getElementById('dashboardView').classList.add('hidden');
            }
        }

        // Login Handler (100% MySQL Authentication via PHP API)
        document.getElementById('loginForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const email = document.getElementById('loginEmail').value.trim();
            const password = document.getElementById('loginPassword').value;
            const errorMsg = document.getElementById('loginErrorMsg');

            errorMsg.classList.add('hidden');

            try {
                const res = await fetch('api/auth_login.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ email, password })
                });

                const result = await res.json();
                if (result.status === 'success') {
                    currentUser = result.user;
                    document.getElementById('loginView').classList.add('hidden');
                    document.getElementById('dashboardView').classList.remove('hidden');
                    document.getElementById('userEmailBadge').textContent = currentUser.email;
                    fetchLocalData();
                } else {
                    errorMsg.textContent = result.message || 'Erro ao realizar login no MySQL.';
                    errorMsg.classList.remove('hidden');
                }
            } catch (err) {
                errorMsg.textContent = 'Erro ao conectar ao servidor MySQL.';
                errorMsg.classList.remove('hidden');
            }
        });

        // Logout Handler
        document.getElementById('logoutBtn').addEventListener('click', async () => {
            await fetch('api/auth_logout.php');
            currentUser = null;
            document.getElementById('loginView').classList.remove('hidden');
            document.getElementById('dashboardView').classList.add('hidden');
        });

        // Fetch Data from Local PHP APIs
        async function fetchLocalData() {
            try {
                const res = await fetch('api/get_products.php');
                const result = await res.json();
                if (result.status === 'success') {
                    loadedProducts = result.data;
                    renderAdminTable();
                }

                const cfgRes = await fetch('api/get_config.php');
                const cfgResult = await cfgRes.json();
                if (cfgResult.status === 'success' && cfgResult.data.publicKey) {
                    document.getElementById('mpPublicKey').value = cfgResult.data.publicKey;
                    document.getElementById('mpStatusBadge').textContent = 'Conectado ✓';
                    document.getElementById('mpStatusBadge').className = 'text-[11px] font-semibold px-3 py-1 rounded-full bg-emerald-100 text-emerald-700';
                }

                fetchAdminOrders();
            } catch (e) {
                console.error("Erro ao buscar dados locais:", e);
            }
        }

        window.fetchAdminOrders = async function() {
            try {
                const res = await fetch('api/get_all_orders.php');
                const result = await res.json();
                if (result.status === 'success') {
                    renderAdminOrdersTable(result.data);
                }
            } catch (err) {
                console.error("Erro ao buscar pedidos:", err);
            }
        };

        function renderAdminOrdersTable(orders) {
            const tbody = document.getElementById('adminOrdersTable');
            if (!orders || orders.length === 0) {
                tbody.innerHTML = `<tr><td colspan="5" class="py-6 text-center text-slate-400">Nenhum pedido realizado ainda.</td></tr>`;
                return;
            }

            tbody.innerHTML = orders.map(o => {
                const isPaid = o.paymentStatus === 'paid';
                const pBadgeClass = isPaid ? 'bg-emerald-100 text-emerald-800' : (o.paymentStatus === 'rejected' ? 'bg-rose-100 text-rose-800' : 'bg-amber-100 text-amber-800');
                const pLabel = isPaid ? 'Pago ✓' : (o.paymentStatus === 'rejected' ? 'Recusado' : 'Aguardando Pagamento');

                const addr = o.address || {};

                return `
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="py-4 px-6 font-medium text-slate-900">
                            <span class="font-bold text-sm text-slate-900 block">${o.orderNumber || o.id}</span>
                            <span class="text-[11px] text-slate-500">${new Date(o.createdAt).toLocaleString()}</span>
                            ${o.paymentId ? `<span class="text-[10px] text-slate-400 font-mono block">MP ID: ${o.paymentId}</span>` : ''}
                        </td>
                        <td class="py-4 px-4">
                            <span class="font-bold text-slate-900 block">${o.customerName || 'Cliente'}</span>
                            <span class="text-slate-500 block">${o.customerEmail}</span>
                            <span class="text-slate-500 block">${o.customerPhone}</span>
                            ${o.customerCpf ? `<span class="text-slate-400 font-mono text-[10px]">CPF: ${o.customerCpf}</span>` : ''}
                        </td>
                        <td class="py-4 px-4 text-slate-700 max-w-xs">
                            <p class="truncate">${addr.street || ''}, ${addr.number || ''} ${addr.complement || ''}</p>
                            <p class="text-slate-500">${addr.neighborhood || ''} - ${addr.city || ''}/${addr.state || ''}</p>
                            <p class="text-slate-400 font-mono text-[10px]">CEP: ${addr.cep || ''}</p>
                        </td>
                        <td class="py-4 px-4">
                            <span class="font-bold text-slate-900 text-sm block">R$ ${o.totalAmount.toFixed(2).replace('.', ',')}</span>
                            <span class="inline-block mt-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold ${pBadgeClass}">
                                ${pLabel}
                            </span>
                        </td>
                        <td class="py-4 px-4">
                            <select onchange="updateOrderStatus('${o.id}', this.value)" class="px-2.5 py-1 rounded-lg border border-slate-300 text-xs font-semibold bg-white text-slate-800 focus:outline-none">
                                <option value="awaiting_payment" ${o.orderStatus === 'awaiting_payment' ? 'selected' : ''}>Aguardando Pagamento</option>
                                <option value="paid" ${o.orderStatus === 'paid' ? 'selected' : ''}>Em Preparação / Pago</option>
                                <option value="shipped" ${o.orderStatus === 'shipped' ? 'selected' : ''}>Enviado / Em Trânsito</option>
                                <option value="delivered" ${o.orderStatus === 'delivered' ? 'selected' : ''}>Entregue</option>
                                <option value="cancelled" ${o.orderStatus === 'cancelled' ? 'selected' : ''}>Cancelado</option>
                            </select>
                        </td>
                    </tr>
                `;
            }).join('');
            lucide.createIcons();
        }

        window.updateOrderStatus = async function(id, newStatus) {
            const res = await fetch('api/update_order_status.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id: id, orderStatus: newStatus })
            });
            const result = await res.json();
            if (result.status === 'success') {
                alert(`Status logístico do pedido atualizado para '${newStatus}'.`);
            } else {
                alert('Erro ao atualizar status do pedido.');
            }
        };

        // Save Mercado Pago keys to local DB
        document.getElementById('mpConfigForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const publicKey = document.getElementById('mpPublicKey').value.trim();
            const accessToken = document.getElementById('mpAccessToken').value.trim();

            const res = await fetch('api/save_config.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ publicKey, accessToken })
            });

            const result = await res.json();
            if (result.status === 'success') {
                alert('Chaves salvas no banco local PHP com sucesso!');
                fetchLocalData();
            } else {
                alert('Erro ao salvar no banco local: ' + result.message);
            }
        });

        // Seed products in local DB
        document.getElementById('seedLocalDbBtn').addEventListener('click', async () => {
            if (confirm('Deseja restaurar os 5 produtos padrões no seu banco de dados local?')) {
                const res = await fetch('api/seed_products.php');
                const result = await res.json();
                alert(result.message);
                fetchLocalData();
            }
        });

        // Render Admin Table
        function renderAdminTable() {
            const tbody = document.getElementById('adminProductsTable');
            tbody.innerHTML = loadedProducts.map(p => `
                <tr class="hover:bg-slate-50/80 transition-colors">
                    <td class="py-4 px-6 font-medium text-slate-900">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-14 bg-slate-100 rounded-lg overflow-hidden shrink-0 flex items-center justify-center border border-slate-200">
                                <img src="${p.image}" alt="${p.name}" class="max-h-full max-w-full object-contain">
                            </div>
                            <div>
                                <span class="font-bold text-slate-900 text-sm block">${p.name}</span>
                                <span class="text-[11px] text-slate-500 font-light">${p.tagline}</span>
                            </div>
                        </div>
                    </td>
                    <td class="py-4 px-4">
                        <span class="text-[10px] font-bold px-2.5 py-1 rounded-full ${p.genderBadge || 'bg-slate-100 text-slate-700'}">
                            ${p.genderTag || 'Unisex'}
                        </span>
                    </td>
                    <td class="py-4 px-4 font-bold text-slate-900">
                        R$ ${(p.price || 49.90).toFixed(2).replace('.', ',')}
                    </td>
                    <td class="py-4 px-4 font-bold text-rose-600">
                        R$ 99,99 <span class="text-[10px] text-slate-400 font-normal">(3 Unidades)</span>
                    </td>
                    <td class="py-4 px-6 text-right">
                        <button onclick="openEditModal('${p.id}')" class="px-3.5 py-1.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-semibold text-xs transition-all shadow-xs inline-flex items-center gap-1.5">
                            <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                            <span>Editar</span>
                        </button>
                    </td>
                </tr>
            `).join('');

            document.getElementById('statTotalProducts').textContent = loadedProducts.length;
            lucide.createIcons();
        }

        // Open Edit Modal
        window.openEditModal = function(id) {
            const p = loadedProducts.find(item => item.id === id);
            if (!p) return;

            document.getElementById('editProductId').value = p.id;
            document.getElementById('editProductName').value = p.name;
            document.getElementById('editProductTagline').value = p.tagline;
            document.getElementById('editProductPrice').value = p.price;
            document.getElementById('editProductGenderTag').value = p.genderTag || 'Feminino';
            document.getElementById('editProductImage').value = p.image;
            document.getElementById('editProductDescription').value = p.description;
            document.getElementById('editNotesTop').value = p.notes ? p.notes.top : '';
            document.getElementById('editNotesHeart').value = p.notes ? p.notes.heart : '';
            document.getElementById('editNotesBase').value = p.notes ? p.notes.base : '';

            document.getElementById('editProductModal').classList.remove('hidden');
        };

        document.getElementById('closeEditModalBtn').onclick = closeEditModal;
        document.getElementById('cancelEditBtn').onclick = closeEditModal;

        function closeEditModal() {
            document.getElementById('editProductModal').classList.add('hidden');
        }

        // Handle Edit Form Submit -> Saves to PHP local DB via API
        document.getElementById('editProductForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const id = document.getElementById('editProductId').value;

            const updatedData = {
                id: id,
                name: document.getElementById('editProductName').value,
                tagline: document.getElementById('editProductTagline').value,
                price: parseFloat(document.getElementById('editProductPrice').value),
                genderTag: document.getElementById('editProductGenderTag').value,
                image: document.getElementById('editProductImage').value,
                description: document.getElementById('editProductDescription').value,
                notes: {
                    top: document.getElementById('editNotesTop').value,
                    heart: document.getElementById('editNotesHeart').value,
                    base: document.getElementById('editNotesBase').value
                }
            };

            const res = await fetch('api/update_product.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(updatedData)
            });

            const result = await res.json();
            if (result.status === 'success') {
                closeEditModal();
                alert('Fragrância atualizada com sucesso no seu banco de dados local PHP!');
                fetchLocalData();
            } else {
                alert('Erro ao atualizar: ' + result.message);
            }
        });

        window.onload = () => {
            checkAdminSession();
            lucide.createIcons();
        };
    </script>
</body>
</html>
