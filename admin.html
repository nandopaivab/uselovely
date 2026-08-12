<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>useLOVELY | Painel Administrativo & CRM Completo</title>

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

    <!-- Login View (Exclusive Protected View) -->
    <div id="loginView" class="min-h-screen flex items-center justify-center p-4 bg-gradient-to-br from-rose-50 via-slate-100 to-purple-50">
        <div class="max-w-md w-full glass-card p-8 rounded-3xl shadow-2xl space-y-6">
            
            <div class="text-center space-y-2">
                <div class="w-12 h-12 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center mx-auto shadow-sm">
                    <i data-lucide="lock" class="w-6 h-6"></i>
                </div>
                <h1 class="font-serif text-3xl font-bold text-slate-900">useLOVELY Admin</h1>
                <p class="text-xs text-slate-500">Painel de Gestão, CRM & Estoque MySQL</p>
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

    <!-- Admin Dashboard View -->
    <div id="dashboardView" class="hidden min-h-screen flex flex-col">
        <!-- Top Navigation Bar -->
        <header class="bg-white border-b border-slate-200 sticky top-0 z-30 shadow-xs">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="font-serif text-xl font-bold text-slate-900">useLOVELY <span class="text-xs uppercase font-sans tracking-widest text-rose-600 bg-rose-50 px-2 py-0.5 rounded-full border border-rose-100 ml-1">CRM & Gestão</span></span>
                </div>
                
                <div class="flex items-center gap-4">
                    <span id="userEmailBadge" class="text-xs text-slate-600 font-medium"></span>
                    <button id="logoutBtn" class="px-3.5 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold flex items-center gap-1.5 transition-all">
                        <i data-lucide="log-out" class="w-3.5 h-3.5"></i>
                        <span>Sair</span>
                    </button>
                </div>
            </div>

            <!-- Tab Buttons Bar -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex space-x-2 border-t border-slate-100 pt-2">
                <button onclick="switchTab('crm')" id="tabBtnCrm" class="px-4 py-2.5 text-xs font-bold uppercase tracking-wider border-b-2 border-rose-500 text-rose-600 flex items-center gap-2">
                    <i data-lucide="bar-chart-3" class="w-4 h-4"></i>
                    <span>Relatórios & CRM Clientes</span>
                </button>
                <button onclick="switchTab('orders')" id="tabBtnOrders" class="px-4 py-2.5 text-xs font-bold uppercase tracking-wider border-b-2 border-transparent text-slate-500 hover:text-slate-800 flex items-center gap-2">
                    <i data-lucide="shopping-cart" class="w-4 h-4"></i>
                    <span>Consulta de Pedidos</span>
                </button>
                <button onclick="switchTab('products')" id="tabBtnProducts" class="px-4 py-2.5 text-xs font-bold uppercase tracking-wider border-b-2 border-transparent text-slate-500 hover:text-slate-800 flex items-center gap-2">
                    <i data-lucide="package" class="w-4 h-4"></i>
                    <span>Estoque & Produtos</span>
                </button>
                <button onclick="switchTab('config')" id="tabBtnConfig" class="px-4 py-2.5 text-xs font-bold uppercase tracking-wider border-b-2 border-transparent text-slate-500 hover:text-slate-800 flex items-center gap-2">
                    <i data-lucide="settings" class="w-4 h-4"></i>
                    <span>Mercado Pago Config</span>
                </button>
            </div>
        </header>

        <!-- Main Content View -->
        <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
            
            <!-- TAB 1: CRM & RELATÓRIOS -->
            <div id="tabContentCrm" class="space-y-8">
                <!-- Metrics Summary Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="p-6 rounded-3xl bg-white border border-slate-200 shadow-sm space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-slate-500 font-semibold uppercase">Faturamento Total</span>
                            <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center">
                                <i data-lucide="dollar-sign" class="w-5 h-5"></i>
                            </div>
                        </div>
                        <h3 id="statTotalRevenue" class="font-serif text-3xl font-bold text-slate-900">R$ 0,00</h3>
                        <span class="text-[11px] text-emerald-600 font-medium">Acumulado em pedidos pagos</span>
                    </div>

                    <div class="p-6 rounded-3xl bg-white border border-slate-200 shadow-sm space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-slate-500 font-semibold uppercase">Total de Pedidos</span>
                            <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">
                                <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                            </div>
                        </div>
                        <h3 id="statTotalOrders" class="font-serif text-3xl font-bold text-slate-900">0</h3>
                        <span id="statPaidOrdersRatio" class="text-[11px] text-blue-600 font-medium">0 pagos</span>
                    </div>

                    <div class="p-6 rounded-3xl bg-white border border-slate-200 shadow-sm space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-slate-500 font-semibold uppercase">Ticket Médio</span>
                            <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center">
                                <i data-lucide="trending-up" class="w-5 h-5"></i>
                            </div>
                        </div>
                        <h3 id="statAvgTicket" class="font-serif text-3xl font-bold text-slate-900">R$ 0,00</h3>
                        <span class="text-[11px] text-purple-600 font-medium">Média por pedido concluído</span>
                    </div>

                    <div class="p-6 rounded-3xl bg-white border border-slate-200 shadow-sm space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-slate-500 font-semibold uppercase">Alertas de Estoque</span>
                            <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center">
                                <i data-lucide="alert-triangle" class="w-5 h-5"></i>
                            </div>
                        </div>
                        <h3 id="statLowStockCount" class="font-serif text-3xl font-bold text-slate-900">0</h3>
                        <span class="text-[11px] text-amber-600 font-medium">Produtos com estoque < 10 un.</span>
                    </div>
                </div>

                <!-- CRM Customer Base Table -->
                <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                        <div>
                            <h2 class="font-serif text-2xl font-bold text-slate-900">CRM - Base de Clientes</h2>
                            <p class="text-xs text-slate-500">Histórico acumulado e valor total gasto por cada cliente (Lifetime Value)</p>
                        </div>
                        <button onclick="loadCrmStats()" class="px-3.5 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs transition-all flex items-center gap-1.5">
                            <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i>
                            <span>Atualizar CRM</span>
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                    <th class="py-3.5 px-6">Cliente</th>
                                    <th class="py-3.5 px-4">Contato / CPF</th>
                                    <th class="py-3.5 px-4 text-center">Total Pedidos</th>
                                    <th class="py-3.5 px-4">Faturamento (LTV)</th>
                                    <th class="py-3.5 px-6">Última Atividade</th>
                                </tr>
                            </thead>
                            <tbody id="crmCustomersTable" class="divide-y divide-slate-100 text-xs">
                                <!-- Injected via JS -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- TAB 2: CONSULTA DE PEDIDOS -->
            <div id="tabContentOrders" class="hidden space-y-6">
                <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden p-6 space-y-4">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div>
                            <h2 class="font-serif text-2xl font-bold text-slate-900">Consulta de Pedidos dos Clientes</h2>
                            <p class="text-xs text-slate-500">Pesquise por nº do pedido, nome, e-mail, CPF ou filtre por status</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="text" id="orderSearchInput" onkeyup="filterOrdersTable()" placeholder="Buscar por pedido, nome, CPF..." class="px-3.5 py-2 rounded-xl border border-slate-300 text-xs w-64 focus:outline-none focus:border-rose-500">
                            <select id="orderStatusFilter" onchange="filterOrdersTable()" class="px-3 py-2 rounded-xl border border-slate-300 text-xs font-semibold bg-white focus:outline-none">
                                <option value="all">Todos os Status</option>
                                <option value="awaiting_payment">Aguardando Pagamento</option>
                                <option value="paid">Pago / Em Preparação</option>
                                <option value="shipped">Enviado</option>
                                <option value="delivered">Entregue</option>
                                <option value="cancelled">Cancelado</option>
                            </select>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                    <th class="py-3.5 px-6">Pedido / Data</th>
                                    <th class="py-3.5 px-4">Cliente / Contato</th>
                                    <th class="py-3.5 px-4">Endereço de Entrega</th>
                                    <th class="py-3.5 px-4">Pagamento & Total</th>
                                    <th class="py-3.5 px-4">Status Logístico</th>
                                </tr>
                            </thead>
                            <tbody id="adminOrdersTable" class="divide-y divide-slate-100 text-xs">
                                <!-- Injected dynamically -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- TAB 3: GESTÃO DE ESTOQUE & PRODUTOS -->
            <div id="tabContentProducts" class="hidden space-y-6">
                <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <h2 class="font-serif text-2xl font-bold text-slate-900">Controle de Estoque e Fragrâncias</h2>
                            <p class="text-xs text-slate-500">Gerencie a quantidade em estoque e faça upload de imagens do seu computador</p>
                        </div>
                        <button id="seedLocalDbBtn" class="px-4 py-2 bg-rose-50 hover:bg-rose-100 text-rose-700 font-semibold text-xs rounded-xl transition-all border border-rose-200 flex items-center gap-2">
                            <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i>
                            <span>Restaurar 5 Produtos no Banco Local</span>
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                    <th class="py-3.5 px-6">Produto</th>
                                    <th class="py-3.5 px-4">Categoria</th>
                                    <th class="py-3.5 px-4">Preço Unitário</th>
                                    <th class="py-3.5 px-4">Estoque Atual</th>
                                    <th class="py-3.5 px-6 text-right">Ações</th>
                                </tr>
                            </thead>
                            <tbody id="adminProductsTable" class="divide-y divide-slate-100 text-xs">
                                <!-- Injected dynamically -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- TAB 4: MERCADO PAGO CONFIG -->
            <div id="tabContentConfig" class="hidden space-y-6">
                <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-lg">
                                MP
                            </div>
                            <div>
                                <h2 class="font-serif text-xl font-bold text-slate-900">Configuração Mercado Pago (Banco Local)</h2>
                                <p class="text-xs text-slate-500">Salva suas chaves de API no banco MySQL local via PHP</p>
                            </div>
                        </div>
                        <span id="mpStatusBadge" class="text-[11px] font-semibold px-3 py-1 rounded-full bg-slate-100 text-slate-600">Não configurado</span>
                    </div>

                    <form id="mpConfigForm" class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Public Key (Chave Pública)</label>
                            <input type="text" id="mpPublicKey" placeholder="Ex: APP_USR-xxxxxx" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:border-blue-500 font-mono text-[11px]">
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Access Token (Chave Privada)</label>
                            <input type="password" id="mpAccessToken" placeholder="Ex: APP_USR-xxxxxx" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:border-blue-500 font-mono text-[11px]">
                        </div>
                        <div class="sm:col-span-2 flex justify-end">
                            <button type="submit" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs transition-all shadow-md flex items-center gap-2">
                                <i data-lucide="check-circle" class="w-4 h-4"></i>
                                <span>Salvar Credenciais</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </main>
    </div>

    <!-- Edit Product & Image Upload Modal -->
    <div id="editProductModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
        <div class="bg-white rounded-3xl max-w-2xl w-full p-6 sm:p-8 relative shadow-2xl max-h-[90vh] overflow-y-auto">
            <button id="closeEditModalBtn" class="absolute top-4 right-4 p-2 text-slate-400 hover:text-slate-700">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>

            <h3 class="font-serif text-2xl font-bold text-slate-900 mb-1">Editar Produto & Upload de Imagem</h3>
            <p class="text-xs text-slate-500 mb-6">Atualize preço, quantidade em estoque e selecione uma foto direto do seu computador.</p>

            <!-- Image Upload Section -->
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 mb-6 space-y-3">
                <span class="font-bold text-slate-800 text-xs block">1. Alterar Foto enviando do seu Computador</span>
                <div class="flex flex-col sm:flex-row items-center gap-4">
                    <img id="editProductImagePreview" src="" class="w-16 h-20 object-contain mix-blend-multiply border rounded-lg bg-white p-1">
                    <div class="flex-1 w-full space-y-2">
                        <input type="file" id="uploadFileImage" accept="image/*" class="text-xs text-slate-600 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-rose-50 file:text-rose-700 hover:file:bg-rose-100">
                        <button type="button" onclick="uploadComputerImage()" class="px-4 py-2 bg-neutral-900 hover:bg-neutral-800 text-white font-semibold text-xs rounded-xl transition-all flex items-center gap-1.5">
                            <i data-lucide="upload" class="w-3.5 h-3.5"></i>
                            <span>Enviar Imagem do Computador</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Edit Fields Form -->
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

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Preço Unitário (R$)</label>
                        <input type="number" step="0.01" id="editProductPrice" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:border-rose-500">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Quantidade em Estoque</label>
                        <input type="number" id="editProductStock" required min="0" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:border-rose-500">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Classificação</label>
                        <select id="editProductGenderTag" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:border-rose-500 bg-white">
                            <option value="Feminino">Feminino</option>
                            <option value="Feminino & Envolvente">Feminino & Envolvente</option>
                            <option value="Unisex / Compartilhável">Unisex / Compartilhável</option>
                            <option value="Masculino & Unisex">Masculino & Unisex</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Caminho da Imagem no Servidor</label>
                    <input type="text" id="editProductImage" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:border-rose-500 font-mono text-[11px]">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Descrição</label>
                    <textarea id="editProductDescription" rows="2" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:border-rose-500"></textarea>
                </div>

                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-3">
                    <span class="font-bold text-slate-800 text-xs block">Pirâmide Olfativa</span>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                        <input type="text" id="editNotesTop" placeholder="Notas de Topo" required class="px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:border-rose-500">
                        <input type="text" id="editNotesHeart" placeholder="Notas de Coração" required class="px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:border-rose-500">
                        <input type="text" id="editNotesBase" placeholder="Notas de Fundo" required class="px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:border-rose-500">
                    </div>
                </div>

                <div class="pt-2 flex justify-end gap-3">
                    <button type="button" id="cancelEditBtn" class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold">
                        Cancelar
                    </button>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-semibold flex items-center gap-2 shadow-md">
                        <i data-lucide="save" class="w-4 h-4"></i>
                        <span>Salvar Produto & Estoque</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- JavaScript Engine -->
    <script>
        let loadedProducts = [];
        let loadedOrders = [];
        let currentUser = null;

        // Session check
        async function checkAdminSession() {
            try {
                const res = await fetch('api/auth_check.php');
                const result = await res.json();
                if (result.loggedIn && result.user) {
                    currentUser = result.user;
                    document.getElementById('loginView').classList.add('hidden');
                    document.getElementById('dashboardView').classList.remove('hidden');
                    document.getElementById('userEmailBadge').textContent = currentUser.email;
                    loadCrmStats();
                    fetchLocalData();
                } else {
                    document.getElementById('loginView').classList.remove('hidden');
                    document.getElementById('dashboardView').classList.add('hidden');
                }
            } catch (e) {
                console.error("Erro na sessão:", e);
            }
        }

        // Login Handler
        document.getElementById('loginForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const email = document.getElementById('loginEmail').value.trim();
            const password = document.getElementById('loginPassword').value;
            const errorMsg = document.getElementById('loginErrorMsg');

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
                    loadCrmStats();
                    fetchLocalData();
                } else {
                    errorMsg.textContent = result.message || 'Login inválido.';
                    errorMsg.classList.remove('hidden');
                }
            } catch (err) {
                errorMsg.textContent = 'Erro de conexão MySQL.';
                errorMsg.classList.remove('hidden');
            }
        });

        document.getElementById('logoutBtn').addEventListener('click', async () => {
            await fetch('api/auth_logout.php');
            currentUser = null;
            document.getElementById('loginView').classList.remove('hidden');
            document.getElementById('dashboardView').classList.add('hidden');
        });

        // Tab Switcher
        window.switchTab = function(tabName) {
            ['crm', 'orders', 'products', 'config'].forEach(t => {
                document.getElementById(`tabContent${t.charAt(0).toUpperCase() + t.slice(1)}`).classList.add('hidden');
                const btn = document.getElementById(`tabBtn${t.charAt(0).toUpperCase() + t.slice(1)}`);
                btn.className = 'px-4 py-2.5 text-xs font-bold uppercase tracking-wider border-b-2 border-transparent text-slate-500 hover:text-slate-800 flex items-center gap-2';
            });

            document.getElementById(`tabContent${tabName.charAt(0).toUpperCase() + tabName.slice(1)}`).classList.remove('hidden');
            document.getElementById(`tabBtn${tabName.charAt(0).toUpperCase() + tabName.slice(1)}`).className = 'px-4 py-2.5 text-xs font-bold uppercase tracking-wider border-b-2 border-rose-500 text-rose-600 flex items-center gap-2';

            if (tabName === 'crm') loadCrmStats();
            if (tabName === 'orders') fetchAdminOrders();
            if (tabName === 'products') fetchLocalData();
        };

        // Load CRM Analytics & Stats
        window.loadCrmStats = async function() {
            try {
                const res = await fetch('api/get_crm_stats.php');
                const result = await res.json();
                if (result.status === 'success') {
                    const m = result.metrics;
                    document.getElementById('statTotalRevenue').textContent = `R$ ${m.totalRevenue.toFixed(2).replace('.', ',')}`;
                    document.getElementById('statTotalOrders').textContent = m.totalOrders;
                    document.getElementById('statPaidOrdersRatio').textContent = `${m.paidOrders} pedidos pagos`;
                    document.getElementById('statAvgTicket').textContent = `R$ ${m.avgTicket.toFixed(2).replace('.', ',')}`;
                    document.getElementById('statLowStockCount').textContent = m.lowStockCount;

                    renderCrmTable(result.customers);
                }
            } catch (err) {
                console.error("Erro ao carregar estatísticas CRM:", err);
            }
        };

        function renderCrmTable(customers) {
            const tbody = document.getElementById('crmCustomersTable');
            if (!customers || customers.length === 0) {
                tbody.innerHTML = `<tr><td colspan="5" class="py-6 text-center text-slate-400">Nenhum cliente cadastrado ainda.</td></tr>`;
                return;
            }

            tbody.innerHTML = customers.map(c => `
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="py-4 px-6 font-medium text-slate-900">
                        <span class="font-bold text-sm text-slate-900 block">${c.name || 'Cliente'}</span>
                        <span class="text-slate-500 block">${c.email}</span>
                    </td>
                    <td class="py-4 px-4 text-slate-600">
                        <span>${c.phone || '-'}</span>
                        ${c.cpf ? `<span class="text-[10px] text-slate-400 block font-mono">CPF: ${c.cpf}</span>` : ''}
                    </td>
                    <td class="py-4 px-4 text-center font-bold text-slate-900">
                        <span class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-700">${c.totalOrders}</span>
                    </td>
                    <td class="py-4 px-4 font-bold text-emerald-600">
                        R$ ${c.totalSpent.toFixed(2).replace('.', ',')}
                    </td>
                    <td class="py-4 px-6 text-slate-500 text-[11px]">
                        ${c.lastPurchaseDate ? new Date(c.lastPurchaseDate).toLocaleString() : '-'}
                    </td>
                </tr>
            `).join('');
        }

        // Fetch Orders & Filter
        window.fetchAdminOrders = async function() {
            try {
                const res = await fetch('api/get_all_orders.php');
                const result = await res.json();
                if (result.status === 'success') {
                    loadedOrders = result.data;
                    filterOrdersTable();
                }
            } catch (err) {
                console.error("Erro ao carregar pedidos:", err);
            }
        };

        window.filterOrdersTable = function() {
            const query = (document.getElementById('orderSearchInput').value || '').toLowerCase();
            const statusFilter = document.getElementById('orderStatusFilter').value;

            let filtered = loadedOrders;
            if (statusFilter !== 'all') {
                filtered = filtered.filter(o => o.orderStatus === statusFilter);
            }

            if (query) {
                filtered = filtered.filter(o => 
                    (o.id && o.id.toLowerCase().includes(query)) ||
                    (o.orderNumber && o.orderNumber.toLowerCase().includes(query)) ||
                    (o.customerName && o.customerName.toLowerCase().includes(query)) ||
                    (o.customerEmail && o.customerEmail.toLowerCase().includes(query)) ||
                    (o.customerCpf && o.customerCpf.toLowerCase().includes(query))
                );
            }

            renderAdminOrdersTable(filtered);
        };

        function renderAdminOrdersTable(orders) {
            const tbody = document.getElementById('adminOrdersTable');
            if (!orders || orders.length === 0) {
                tbody.innerHTML = `<tr><td colspan="5" class="py-6 text-center text-slate-400">Nenhum pedido encontrado.</td></tr>`;
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
                alert(`Status do pedido atualizado para '${newStatus}'.`);
            }
        };

        // Fetch Products Data
        async function fetchLocalData() {
            try {
                const res = await fetch('api/get_products.php');
                const result = await res.json();
                if (result.status === 'success') {
                    loadedProducts = result.data;
                    renderAdminProductsTable();
                }

                const cfgRes = await fetch('api/get_config.php');
                const cfgResult = await cfgRes.json();
                if (cfgResult.status === 'success' && cfgResult.data.publicKey) {
                    document.getElementById('mpPublicKey').value = cfgResult.data.publicKey;
                    document.getElementById('mpStatusBadge').textContent = 'Conectado ✓';
                    document.getElementById('mpStatusBadge').className = 'text-[11px] font-semibold px-3 py-1 rounded-full bg-emerald-100 text-emerald-700';
                }
            } catch (e) {
                console.error("Erro ao carregar dados dos produtos:", e);
            }
        }

        function renderAdminProductsTable() {
            const tbody = document.getElementById('adminProductsTable');
            tbody.innerHTML = loadedProducts.map(p => {
                const stock = p.stockQuantity || 100;
                const isLowStock = stock < 10;
                const stockBadgeClass = isLowStock ? 'bg-amber-100 text-amber-800 border-amber-300' : 'bg-emerald-100 text-emerald-800 border-emerald-300';

                return `
                    <tr class="hover:bg-slate-50 transition-colors">
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
                        <td class="py-4 px-4">
                            <span class="px-3 py-1 rounded-full text-xs font-bold border ${stockBadgeClass}">
                                ${stock} un. ${isLowStock ? '(Baixo!)' : ''}
                            </span>
                        </td>
                        <td class="py-4 px-6 text-right">
                            <button onclick="openEditModal('${p.id}')" class="px-3.5 py-1.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-semibold text-xs transition-all shadow-xs inline-flex items-center gap-1.5">
                                <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                                <span>Editar / Upload Foto</span>
                            </button>
                        </td>
                    </tr>
                `;
            }).join('');
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
            document.getElementById('editProductStock').value = p.stockQuantity || 100;
            document.getElementById('editProductGenderTag').value = p.genderTag || 'Feminino';
            document.getElementById('editProductImage').value = p.image;
            document.getElementById('editProductImagePreview').src = p.image;
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

        // Upload Computer Image
        window.uploadComputerImage = async function() {
            const productId = document.getElementById('editProductId').value;
            const fileInput = document.getElementById('uploadFileImage');

            if (!fileInput.files || fileInput.files.length === 0) {
                alert('Selecione um arquivo de imagem no seu computador primeiro.');
                return;
            }

            const formData = new FormData();
            formData.append('product_id', productId);
            formData.append('product_image', fileInput.files[0]);

            try {
                const res = await fetch('api/upload_product_image.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await res.json();
                if (result.status === 'success') {
                    document.getElementById('editProductImage').value = result.imagePath;
                    document.getElementById('editProductImagePreview').src = result.imagePath;
                    alert('Imagem enviada e atualizada com sucesso no servidor e banco MySQL!');
                    fetchLocalData();
                } else {
                    alert('Erro no upload: ' + result.message);
                }
            } catch (err) {
                alert('Erro ao enviar imagem.');
            }
        };

        // Submit Edit Form
        document.getElementById('editProductForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const id = document.getElementById('editProductId').value;

            const updatedData = {
                id: id,
                name: document.getElementById('editProductName').value,
                tagline: document.getElementById('editProductTagline').value,
                price: parseFloat(document.getElementById('editProductPrice').value),
                stockQuantity: parseInt(document.getElementById('editProductStock').value),
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
                alert('Produto e quantidade em estoque salvos com sucesso!');
                fetchLocalData();
                loadCrmStats();
            } else {
                alert('Erro ao atualizar: ' + result.message);
            }
        });

        // Save Mercado Pago Config
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
                alert('Credenciais salvas com sucesso!');
                fetchLocalData();
            } else {
                alert('Erro: ' + result.message);
            }
        });

        // Seed Default Products
        document.getElementById('seedLocalDbBtn').addEventListener('click', async () => {
            if (confirm('Deseja restaurar os 5 produtos padrões no seu banco de dados local?')) {
                const res = await fetch('api/seed_products.php');
                const result = await res.json();
                alert(result.message);
                fetchLocalData();
            }
        });

        window.onload = () => {
            checkAdminSession();
            lucide.createIcons();
        };
    </script>
</body>
</html>
