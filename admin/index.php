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
                <button id="tabBtnCrm" onclick="switchTab('crm')" class="px-4 py-2.5 text-xs font-bold uppercase tracking-wider border-b-2 border-rose-500 text-rose-600 flex items-center gap-2">
                    <i data-lucide="bar-chart-3" class="w-4 h-4"></i>
                    <span>Relatórios & CRM Clientes</span>
                </button>
                <button id="tabBtnOrders" onclick="switchTab('orders')" class="px-4 py-2.5 text-xs font-bold uppercase tracking-wider border-b-2 border-transparent text-slate-500 hover:text-slate-800 flex items-center gap-2">
                    <i data-lucide="shopping-bag" class="w-4 h-4"></i>
                    <span>Consulta de Pedidos</span>
                </button>
                <button id="tabBtnProducts" onclick="switchTab('products')" class="px-4 py-2.5 text-xs font-bold uppercase tracking-wider border-b-2 border-transparent text-slate-500 hover:text-slate-800 flex items-center gap-2">
                    <i data-lucide="box" class="w-4 h-4"></i>
                    <span>Estoque & Produtos</span>
                </button>
                <button id="tabBtnConfig" onclick="switchTab('config')" class="px-4 py-2.5 text-xs font-bold uppercase tracking-wider border-b-2 border-transparent text-slate-500 hover:text-slate-800 flex items-center gap-2">
                    <i data-lucide="settings" class="w-4 h-4"></i>
                    <span>Configurações da Loja</span>
                </button>
                <button id="tabBtnCoupons" onclick="switchTab('coupons')" class="px-4 py-2.5 text-xs font-bold uppercase tracking-wider border-b-2 border-transparent text-slate-500 hover:text-slate-800 flex items-center gap-2">
                    <i data-lucide="tag" class="w-4 h-4"></i>
                    <span>Gestão de Cupons</span>
                </button>
            </div>
        </header>

        <!-- Main Content Container -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex-1 w-full space-y-8">
            
            <!-- TAB 1: CRM & RELATÓRIOS -->
            <div id="tabContentCrm" class="space-y-8">
                <!-- Executive Metrics Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-xs space-y-2">
                        <div class="flex items-center justify-between text-slate-500">
                            <span class="text-xs font-semibold uppercase tracking-wider">Faturamento Total</span>
                            <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                                <i data-lucide="dollar-sign" class="w-4 h-4"></i>
                            </div>
                        </div>
                        <p id="statTotalRevenue" class="font-serif text-3xl font-bold text-slate-900">R$ 0,00</p>
                        <span class="text-[11px] text-emerald-600 font-semibold flex items-center gap-1">
                            <i data-lucide="trending-up" class="w-3 h-3"></i> Vendas Confirmadas
                        </span>
                    </div>

                    <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-xs space-y-2">
                        <div class="flex items-center justify-between text-slate-500">
                            <span class="text-xs font-semibold uppercase tracking-wider">Total de Pedidos</span>
                            <div class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center">
                                <i data-lucide="shopping-cart" class="w-4 h-4"></i>
                            </div>
                        </div>
                        <p id="statTotalOrders" class="font-serif text-3xl font-bold text-slate-900">0</p>
                        <span id="statPaidOrdersRatio" class="text-[11px] text-slate-500 font-medium">0 pedidos pagos</span>
                    </div>

                    <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-xs space-y-2">
                        <div class="flex items-center justify-between text-slate-500">
                            <span class="text-xs font-semibold uppercase tracking-wider">Ticket Médio</span>
                            <div class="w-8 h-8 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
                                <i data-lucide="award" class="w-4 h-4"></i>
                            </div>
                        </div>
                        <p id="statAvgTicket" class="font-serif text-3xl font-bold text-slate-900">R$ 0,00</p>
                        <span class="text-[11px] text-slate-500 font-medium">Média por pedido</span>
                    </div>

                    <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-xs space-y-2">
                        <div class="flex items-center justify-between text-slate-500">
                            <span class="text-xs font-semibold uppercase tracking-wider">Alerta de Estoque</span>
                            <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                                <i data-lucide="alert-triangle" class="w-4 h-4"></i>
                            </div>
                        </div>
                        <p id="statLowStockCount" class="font-serif text-3xl font-bold text-slate-900">0</p>
                        <span class="text-[11px] text-amber-600 font-semibold">Produtos com estoque &lt; 10 un.</span>
                    </div>
                </div>

                <!-- Customer Base Table -->
                <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                        <div>
                            <h2 class="font-serif text-2xl font-bold text-slate-900">Base de Clientes & CRM</h2>
                            <p class="text-xs text-slate-500">Histórico de compras e contatos de clientes cadastrados</p>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                    <th class="py-3.5 px-6">Cliente / E-mail</th>
                                    <th class="py-3.5 px-4">Telefone / CPF</th>
                                    <th class="py-3.5 px-4 text-center">Total Pedidos</th>
                                    <th class="py-3.5 px-4">Total Gasto</th>
                                    <th class="py-3.5 px-6">Última Compra</th>
                                </tr>
                            </thead>
                            <tbody id="crmCustomersTable" class="divide-y divide-slate-100 text-xs">
                                <!-- Injected dynamically -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- TAB 2: CONSULTA DE PEDIDOS -->
            <div id="tabContentOrders" class="hidden space-y-6">
                <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
                    
                    <div class="p-6 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div>
                            <h2 class="font-serif text-2xl font-bold text-slate-900">Consulta de Pedidos Online</h2>
                            <p class="text-xs text-slate-500">Gerencie todos os pedidos efetuados pelo site e atualize status de envio</p>
                        </div>

                        <div class="flex items-center gap-3">
                            <input type="text" id="orderSearchInput" onkeyup="filterOrdersTable()" placeholder="Buscar por número, nome, email ou CPF..." class="px-3.5 py-2 rounded-xl border border-slate-300 text-xs focus:outline-none focus:border-rose-500 w-64">
                            
                            <select id="orderStatusFilter" onchange="filterOrdersTable()" class="px-3.5 py-2 rounded-xl border border-slate-300 text-xs font-semibold bg-white focus:outline-none focus:border-rose-500">
                                <option value="all">Todos os Status</option>
                                <option value="awaiting_payment">Aguardando Pagamento</option>
                                <option value="paid">Pagos / Em Preparação</option>
                                <option value="shipped">Enviados</option>
                                <option value="delivered">Entregues</option>
                                <option value="cancelled">Cancelados</option>
                            </select>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                    <th class="py-3.5 px-6">Nº do Pedido / Data</th>
                                    <th class="py-3.5 px-4">Cliente / Contato</th>
                                    <th class="py-3.5 px-4">Endereço de Entrega</th>
                                    <th class="py-3.5 px-4">Valor & Pagamento</th>
                                    <th class="py-3.5 px-4">Status do Pedido</th>
                                </tr>
                            </thead>
                            <tbody id="adminOrdersTable" class="divide-y divide-slate-100 text-xs">
                                <!-- Injected dynamically -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- TAB 3: ESTOQUE & PRODUTOS -->
            <div id="tabContentProducts" class="hidden space-y-6">
                <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <h2 class="font-serif text-2xl font-bold text-slate-900">Controle de Estoque e Fragrâncias</h2>
                            <p class="text-xs text-slate-500">Adicione novos produtos, gerencie referências olfativas, estoque e faça upload de imagens</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <button onclick="openCreateProductModal()" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs rounded-xl transition-all shadow-md flex items-center gap-1.5">
                                <i data-lucide="plus-circle" class="w-4 h-4"></i>
                                <span>+ Novo Produto</span>
                            </button>
                            <button id="seedLocalDbBtn" class="px-4 py-2 bg-rose-50 hover:bg-rose-100 text-rose-700 font-semibold text-xs rounded-xl transition-all border border-rose-200 flex items-center gap-1.5">
                                <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i>
                                <span>Restaurar 5 Padrões</span>
                            </button>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                    <th class="py-3.5 px-6">Produto</th>
                                    <th class="py-3.5 px-4">Referência Olfativa</th>
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
                <!-- Promo Pricing Block -->
                <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center font-bold text-lg">
                                <i data-lucide="tag" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <h2 class="font-serif text-xl font-bold text-slate-900">Preços e Promoções</h2>
                                <p class="text-xs text-slate-500">Configure os valores exibidos na loja para a compra de produtos</p>
                            </div>
                        </div>
                    </div>
                    <form id="promoConfigForm" class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Preço Unitário Padrão (R$)</label>
                            <input type="text" id="promoSinglePrice" placeholder="Ex: 49,90" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:border-rose-500">
                        </div>
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Preço Combo Trio (R$)</label>
                            <input type="text" id="promoComboPrice" placeholder="Ex: 99,99" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:border-rose-500">
                        </div>
                        <div class="sm:col-span-2 flex justify-end">
                            <button type="submit" class="px-5 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-semibold text-xs transition-all shadow-md flex items-center gap-2">
                                <i data-lucide="check-circle" class="w-4 h-4"></i>
                                <span>Salvar Preços</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- MP Block -->
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

            <!-- TAB: COUPONS -->
            <div id="tabContentCoupons" class="hidden space-y-6">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-6 rounded-3xl border border-slate-200 shadow-sm">
                    <div>
                        <h2 class="font-serif text-2xl font-bold text-slate-900 mb-1">Cupons de Desconto</h2>
                        <p class="text-xs text-slate-500">Crie e gerencie os códigos promocionais da sua loja.</p>
                    </div>
                    <button onclick="openCouponModal()" class="px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-semibold text-xs rounded-xl shadow-md flex items-center gap-2 transition-all">
                        <i data-lucide="plus" class="w-4 h-4"></i>
                        <span>Novo Cupom</span>
                    </button>
                </div>
                
                <div class="bg-white border border-slate-200 rounded-3xl overflow-hidden shadow-sm">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs whitespace-nowrap">
                            <thead class="bg-slate-50 text-slate-600 font-semibold uppercase tracking-wider border-b border-slate-200">
                                <tr>
                                    <th class="px-6 py-4">Código</th>
                                    <th class="px-6 py-4">Tipo & Valor</th>
                                    <th class="px-6 py-4">Cliente Específico</th>
                                    <th class="px-6 py-4 text-center">Usos & Validade</th>
                                    <th class="px-6 py-4 text-right">Ações</th>
                                </tr>
                            </thead>
                            <tbody id="couponsTableBody" class="divide-y divide-slate-100 text-slate-700">
                                <!-- JS injected -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </main>
    </div>

    <!-- Add Coupon Modal -->
    <div id="addCouponModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 relative shadow-2xl">
            <button onclick="closeCouponModal()" class="absolute top-4 right-4 p-2 text-slate-400 hover:text-slate-700">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
            <h3 class="font-serif text-2xl font-bold text-slate-900 mb-1">Novo Cupom</h3>
            <p class="text-xs text-slate-500 mb-6">Crie um código de desconto personalizado.</p>

            <form id="addCouponForm" class="space-y-4 text-xs">
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Código Promocional</label>
                    <input type="text" id="couponCode" required placeholder="Ex: INVERNO20" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:border-rose-500 uppercase">
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Tipo de Desconto</label>
                        <select id="couponType" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:border-rose-500 bg-white">
                            <option value="percentage">Porcentagem (%)</option>
                            <option value="fixed">Valor Fixo (R$)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Valor do Desconto</label>
                        <input type="number" step="0.01" id="couponValue" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:border-rose-500">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Limite de Usos (Total)</label>
                        <input type="number" id="couponLimit" value="1" min="1" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:border-rose-500">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">E-mail Cliente Específico (Opcional)</label>
                        <input type="email" id="couponUserEmail" placeholder="Deixe em branco p/ todos" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:border-rose-500">
                    </div>
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Data de Validade (Opcional)</label>
                    <input type="datetime-local" id="couponExpiresAt" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:border-rose-500 text-slate-500">
                </div>

                <div class="pt-4 flex justify-end gap-3">
                    <button type="button" onclick="closeCouponModal()" class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold">Cancelar</button>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-semibold flex items-center gap-2 shadow-md">
                        <i data-lucide="check" class="w-4 h-4"></i> Criar Cupom
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Coupon Modal -->
    <div id="editCouponModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 relative shadow-2xl">
            <button onclick="closeEditCouponModal()" class="absolute top-4 right-4 p-2 text-slate-400 hover:text-slate-700">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
            <h3 class="font-serif text-2xl font-bold text-slate-900 mb-1">Editar Cupom</h3>
            <p class="text-xs text-slate-500 mb-6">Altere as regras e validade do cupom selecionado.</p>

            <form id="editCouponForm" class="space-y-4 text-xs">
                <input type="hidden" id="editCouponId">
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Código Promocional</label>
                    <input type="text" id="editCouponCode" disabled class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-slate-400 focus:outline-none uppercase cursor-not-allowed">
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Tipo de Desconto</label>
                        <select id="editCouponType" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:border-rose-500 bg-white">
                            <option value="percentage">Porcentagem (%)</option>
                            <option value="fixed">Valor Fixo (R$)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Valor do Desconto</label>
                        <input type="number" step="0.01" id="editCouponValue" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:border-rose-500">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Limite de Usos (Total)</label>
                        <input type="number" id="editCouponLimit" value="1" min="1" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:border-rose-500">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">E-mail Cliente Específico</label>
                        <input type="email" id="editCouponUserEmail" placeholder="Deixe em branco p/ todos" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:border-rose-500">
                    </div>
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Data de Validade (Opcional)</label>
                    <input type="datetime-local" id="editCouponExpiresAt" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:border-rose-500 text-slate-500">
                </div>

                <div class="pt-4 flex justify-end gap-3">
                    <button type="button" onclick="closeEditCouponModal()" class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold">Cancelar</button>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-semibold flex items-center gap-2 shadow-md">
                        <i data-lucide="save" class="w-4 h-4"></i> Salvar Cupom
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit & Add Product Modal -->
    <div id="editProductModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
        <div class="bg-white rounded-3xl max-w-2xl w-full p-6 sm:p-8 relative shadow-2xl max-h-[90vh] overflow-y-auto">
            <button id="closeEditModalBtn" class="absolute top-4 right-4 p-2 text-slate-400 hover:text-slate-700">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>

            <h3 id="editModalTitle" class="font-serif text-2xl font-bold text-slate-900 mb-1">Editar Produto</h3>
            <p id="editModalSubtitle" class="text-xs text-slate-500 mb-6">Atualize preço, referência olfativa, quantidade em estoque e foto do computador.</p>

            <!-- Image Upload Section -->
            <div id="imageUploadSection" class="p-4 rounded-2xl bg-slate-50 border border-slate-200 mb-6 space-y-3">
                <span class="font-bold text-slate-800 text-xs block">1. Foto do Produto (Upload do seu Computador)</span>
                <div class="flex flex-col sm:flex-row items-center gap-4">
                    <img id="editProductImagePreview" src="../assets/images/velvet_bloom.jpg" class="w-16 h-20 object-contain mix-blend-multiply border rounded-lg bg-white p-1">
                    <div class="flex-1 w-full space-y-2">
                        <input type="file" id="uploadFileImage" accept="image/*" class="text-xs text-slate-600 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-rose-50 file:text-rose-700 hover:file:bg-rose-100">
                        <button type="button" onclick="uploadComputerImage()" class="px-4 py-2 bg-neutral-900 hover:bg-neutral-800 text-white font-semibold text-xs rounded-xl transition-all flex items-center gap-1.5">
                            <i data-lucide="upload" class="w-3.5 h-3.5"></i>
                            <span>Enviar Imagem do Computador</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Edit / Add Fields Form -->
            <form id="editProductForm" class="space-y-4 text-xs">
                <input type="hidden" id="editProductId">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Nome da Fragrância</label>
                        <input type="text" id="editProductName" placeholder="Ex: Velvet Bloom" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:border-rose-500">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Subtítulo / Tagline</label>
                        <input type="text" id="editProductTagline" placeholder="Ex: Floral Gourmand & Elegante" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:border-rose-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Referência Olfativa (Inspiração / Contratipo)</label>
                        <input type="text" id="editProductOlfactoryRef" placeholder="Ex: La Vie Est Belle (Lancôme)" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:border-rose-500 font-medium text-rose-700">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Classificação / Gênero</label>
                        <select id="editProductGenderTag" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:border-rose-500 bg-white">
                            <option value="Feminino">Feminino</option>
                            <option value="Feminino & Envolvente">Feminino & Envolvente</option>
                            <option value="Unisex / Compartilhável">Unisex / Compartilhável</option>
                            <option value="Masculino & Unisex">Masculino & Unisex</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Preço Unitário (R$)</label>
                        <input type="number" step="0.01" id="editProductPrice" value="49.90" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:border-rose-500">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Quantidade em Estoque</label>
                        <input type="number" id="editProductStock" value="100" required min="0" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:border-rose-500">
                    </div>
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Caminho da Imagem no Servidor</label>
                    <input type="text" id="editProductImage" placeholder="assets/images/exemplo.jpg" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:border-rose-500 font-mono text-[11px]">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Descrição Detalhada</label>
                    <textarea id="editProductDescription" rows="2" placeholder="Descreva as características sensoriais da fragrância..." required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:border-rose-500"></textarea>
                </div>

                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-3">
                    <span class="font-bold text-slate-800 text-xs block">Pirâmide Olfativa</span>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                        <input type="text" id="editNotesTop" placeholder="Notas de Topo (Saída)" required class="px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:border-rose-500">
                        <input type="text" id="editNotesHeart" placeholder="Notas de Coração (Corpo)" required class="px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:border-rose-500">
                        <input type="text" id="editNotesBase" placeholder="Notas de Fundo (Fixação)" required class="px-3 py-2 rounded-lg border border-slate-300 focus:outline-none focus:border-rose-500">
                    </div>
                </div>

                <div class="pt-3 border-t border-slate-100 flex items-center justify-between gap-3">
                    <button type="button" id="modalDeleteBtn" onclick="confirmDeleteFromModal()" class="hidden px-4 py-2.5 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200 font-semibold text-xs flex items-center gap-1.5">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                        <span>Excluir Produto</span>
                    </button>

                    <div class="flex items-center gap-3 ml-auto">
                        <button type="button" id="cancelEditBtn" class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold">
                            Cancelar
                        </button>
                        <button type="submit" id="saveProductBtn" class="px-6 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-semibold flex items-center gap-2 shadow-md">
                            <i data-lucide="save" class="w-4 h-4"></i>
                            <span id="saveProductBtnText">Salvar Produto</span>
                        </button>
                    </div>
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
                const res = await fetch('../api/auth_check.php');
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
                const res = await fetch('../api/auth_login.php', {
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
            await fetch('../api/auth_logout.php');
            currentUser = null;
            document.getElementById('loginView').classList.remove('hidden');
            document.getElementById('dashboardView').classList.add('hidden');
        });

        // Tab Switcher
        function switchTab(tabId) {
            const tabs = ['crm', 'orders', 'products', 'config', 'coupons'];
            tabs.forEach(t => {
                document.getElementById('tabContent' + t.charAt(0).toUpperCase() + t.slice(1)).classList.add('hidden');
                
                const btn = document.getElementById('tabBtn' + t.charAt(0).toUpperCase() + t.slice(1));
                btn.classList.remove('border-rose-500', 'text-rose-600');
                btn.classList.add('border-transparent', 'text-slate-500');
            });

            document.getElementById('tabContent' + tabId.charAt(0).toUpperCase() + tabId.slice(1)).classList.remove('hidden');
            const activeBtn = document.getElementById('tabBtn' + tabId.charAt(0).toUpperCase() + tabId.slice(1));
            activeBtn.classList.add('border-rose-500', 'text-rose-600');
            activeBtn.classList.remove('border-transparent', 'text-slate-500');

            if (tabId === 'crm') loadCrmStats();
            if (tabId === 'orders') fetchAdminOrders();
            if (tabId === 'products') fetchLocalData();
            if (tabId === 'coupons') fetchCoupons();
        };

        // Load CRM Analytics & Stats
        window.loadCrmStats = async function() {
            try {
                const res = await fetch('../api/get_crm_stats.php');
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
                const res = await fetch('../api/get_all_orders.php');
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
            const res = await fetch('../api/update_order_status.php', {
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
                const res = await fetch('../api/get_products.php');
                const result = await res.json();
                if (result.status === 'success') {
                    loadedProducts = result.data;
                    renderAdminProductsTable();
                }

            } catch (e) {
                console.error("Erro ao carregar dados locais:", e);
            }
        }

        function renderAdminProductsTable() {
            const tbody = document.getElementById('adminProductsTable');
            if (!loadedProducts || loadedProducts.length === 0) {
                tbody.innerHTML = `<tr><td colspan="5" class="py-8 text-center text-slate-400">Nenhum produto cadastrado. Clique em "+ Novo Produto" acima para adicionar.</td></tr>`;
                return;
            }

            tbody.innerHTML = loadedProducts.map(p => {
                const stock = p.stockQuantity || 100;
                const isLowStock = stock < 10;
                const stockBadgeClass = isLowStock ? 'bg-amber-100 text-amber-800 border-amber-300' : 'bg-emerald-100 text-emerald-800 border-emerald-300';
                const safeName = p.name.replace(/'/g, "\\'");
                const imgPath = p.image.startsWith('/') || p.image.startsWith('http') || p.image.startsWith('../') ? p.image : '../' + p.image;

                return `
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="py-4 px-6 font-medium text-slate-900">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-14 bg-slate-100 rounded-lg overflow-hidden shrink-0 flex items-center justify-center border border-slate-200">
                                    <img src="${imgPath}" alt="${p.name}" class="max-h-full max-w-full object-contain">
                                </div>
                                <div>
                                    <span class="font-bold text-slate-900 text-sm block">${p.name}</span>
                                    <span class="text-[11px] text-slate-500 font-light">${p.tagline}</span>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-4">
                            <span class="text-[11px] font-bold text-rose-700 bg-rose-50 px-2.5 py-1 rounded-full border border-rose-200">
                                ${p.olfactoryReference || 'Nenhuma'}
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
                        <td class="py-4 px-6 text-right space-x-2">
                            <button onclick="openEditModal('${p.id}')" class="px-3 py-1.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-semibold text-xs transition-all shadow-xs inline-flex items-center gap-1">
                                <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                                <span>Editar</span>
                            </button>
                            <button onclick="deleteProduct('${p.id}', '${safeName}')" class="px-3 py-1.5 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200 font-semibold text-xs transition-all inline-flex items-center gap-1">
                                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                <span>Excluir</span>
                            </button>
                        </td>
                    </tr>
                `;
            }).join('');
            lucide.createIcons();
        }

        // Open Modal to Create New Product
        window.openCreateProductModal = function() {
            document.getElementById('editModalTitle').textContent = 'Adicionar Novo Produto';
            document.getElementById('editModalSubtitle').textContent = 'Preencha as informações da nova fragrância para disponibilizá-la na loja online.';

            document.getElementById('editProductId').value = '';
            document.getElementById('editProductName').value = '';
            document.getElementById('editProductTagline').value = '';
            document.getElementById('editProductOlfactoryRef').value = '';
            document.getElementById('editProductPrice').value = '49.90';
            document.getElementById('editProductStock').value = '100';
            document.getElementById('editProductGenderTag').value = 'Feminino';
            document.getElementById('editProductImage').value = 'assets/images/velvet_bloom.jpg';
            document.getElementById('editProductImagePreview').src = '../assets/images/velvet_bloom.jpg';
            document.getElementById('editProductDescription').value = '';
            document.getElementById('editNotesTop').value = '';
            document.getElementById('editNotesHeart').value = '';
            document.getElementById('editNotesBase').value = '';

            document.getElementById('modalDeleteBtn').classList.add('hidden');
            document.getElementById('saveProductBtnText').textContent = 'Cadastrar Produto';

            document.getElementById('editProductModal').classList.remove('hidden');
        };

        // Open Modal to Edit Existing Product
        window.openEditModal = function(id) {
            const p = loadedProducts.find(item => item.id === id);
            if (!p) return;

            document.getElementById('editModalTitle').textContent = `Editar: ${p.name}`;
            document.getElementById('editModalSubtitle').textContent = 'Atualize preço, referência olfativa, quantidade em estoque e foto do produto.';

            const imgPreview = p.image.startsWith('/') || p.image.startsWith('http') || p.image.startsWith('../') ? p.image : '../' + p.image;

            document.getElementById('editProductId').value = p.id;
            document.getElementById('editProductName').value = p.name;
            document.getElementById('editProductTagline').value = p.tagline;
            document.getElementById('editProductOlfactoryRef').value = p.olfactoryReference || '';
            document.getElementById('editProductPrice').value = p.price;
            document.getElementById('editProductStock').value = p.stockQuantity || 100;
            document.getElementById('editProductGenderTag').value = p.genderTag || 'Feminino';
            document.getElementById('editProductImage').value = p.image;
            document.getElementById('editProductImagePreview').src = imgPreview;
            document.getElementById('editProductDescription').value = p.description;
            document.getElementById('editNotesTop').value = p.notes ? p.notes.top : (p.notes_top || '');
            document.getElementById('editNotesHeart').value = p.notes ? p.notes.heart : (p.notes_heart || '');
            document.getElementById('editNotesBase').value = p.notes ? p.notes.base : (p.notes_base || '');

            document.getElementById('modalDeleteBtn').classList.remove('hidden');
            document.getElementById('saveProductBtnText').textContent = 'Salvar Alterações';

            document.getElementById('editProductModal').classList.remove('hidden');
        };

        document.getElementById('closeEditModalBtn').onclick = closeEditModal;
        document.getElementById('cancelEditBtn').onclick = closeEditModal;

        function closeEditModal() {
            document.getElementById('editProductModal').classList.add('hidden');
        }

        // Delete Product API call
        window.deleteProduct = async function(id, name) {
            if (!confirm(`Tem certeza de que deseja excluir permanentemente o produto "${name}"?`)) {
                return;
            }

            try {
                const res = await fetch('../api/delete_product.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id: id })
                });

                const result = await res.json();
                if (result.status === 'success') {
                    alert(result.message);
                    fetchLocalData();
                    loadCrmStats();
                } else {
                    alert('Erro ao excluir: ' + result.message);
                }
            } catch (err) {
                alert('Erro ao conectar ao servidor para excluir produto.');
            }
        };

        window.confirmDeleteFromModal = function() {
            const id = document.getElementById('editProductId').value;
            const name = document.getElementById('editProductName').value;
            if (id) {
                closeEditModal();
                deleteProduct(id, name);
            }
        };

        // Upload Computer Image
        window.uploadComputerImage = async function() {
            const productId = document.getElementById('editProductId').value || 'new-product';
            const fileInput = document.getElementById('uploadFileImage');

            if (!fileInput.files || fileInput.files.length === 0) {
                alert('Selecione um arquivo de imagem no seu computador primeiro.');
                return;
            }

            const formData = new FormData();
            formData.append('product_id', productId);
            formData.append('product_image', fileInput.files[0]);

            try {
                const res = await fetch('../api/upload_product_image.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await res.json();
                if (result.status === 'success') {
                    document.getElementById('editProductImage').value = result.imagePath;
                    const previewPath = result.imagePath.startsWith('/') || result.imagePath.startsWith('http') || result.imagePath.startsWith('../') ? result.imagePath : '../' + result.imagePath;
                    document.getElementById('editProductImagePreview').src = previewPath;
                    alert('Imagem enviada e carregada com sucesso!');
                } else {
                    alert('Erro no upload: ' + result.message);
                }
            } catch (err) {
                alert('Erro ao enviar imagem.');
            }
        };

        // Submit Form for Add or Edit Product
        document.getElementById('editProductForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const id = document.getElementById('editProductId').value.trim();

            const productPayload = {
                id: id,
                name: document.getElementById('editProductName').value,
                tagline: document.getElementById('editProductTagline').value,
                olfactoryReference: document.getElementById('editProductOlfactoryRef').value,
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

            const endpoint = id ? '../api/update_product.php' : '../api/add_product.php';

            try {
                const res = await fetch(endpoint, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(productPayload)
                });

                const result = await res.json();
                if (result.status === 'success') {
                    closeEditModal();
                    alert(result.message);
                    fetchLocalData();
                    loadCrmStats();
                } else {
                    alert('Erro ao salvar produto: ' + result.message);
                }
            } catch (err) {
                alert('Erro na comunicação com o servidor MySQL.');
            }
        });

        // Save Promo Config
        document.getElementById('promoConfigForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const promoSinglePrice = document.getElementById('promoSinglePrice').value.trim();
            const promoComboPrice = document.getElementById('promoComboPrice').value.trim();

            const res = await fetch('../api/save_config.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ promoSinglePrice, promoComboPrice })
            });

            const result = await res.json();
            if (result.status === 'success') {
                alert('Preços salvos com sucesso!');
                fetchLocalData();
            } else {
                alert('Erro: ' + result.message);
            }
        });

        // Save Mercado Pago Config
        document.getElementById('mpConfigForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const publicKey = document.getElementById('mpPublicKey').value.trim();
            const accessToken = document.getElementById('mpAccessToken').value.trim();

            const res = await fetch('../api/save_config.php', {
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
            if (confirm('Deseja restaurar os 5 produtos padrões com suas referências olfativas no seu banco de dados local?')) {
                const res = await fetch('../api/seed_products.php');
                const result = await res.json();
                alert(result.message);
                fetchLocalData();
            }
        });

        let allCoupons = [];

        async function fetchCoupons() {
            try {
                const res = await fetch('../api/admin_get_coupons.php');
                const result = await res.json();
                
                if (result.status === 'success') {
                    allCoupons = result.data;
                    const tbody = document.getElementById('couponsTableBody');
                    if (allCoupons.length === 0) {
                        tbody.innerHTML = `<tr><td colspan="5" class="px-6 py-8 text-center text-slate-400">Nenhum cupom encontrado no banco.</td></tr>`;
                    } else {
                        tbody.innerHTML = allCoupons.map(c => `
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 font-mono font-bold text-slate-900">${c.code}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-[10px] font-bold ${c.type === 'percentage' ? 'bg-indigo-50 text-indigo-700' : 'bg-emerald-50 text-emerald-700'}">
                                        ${c.type === 'percentage' ? c.value + '%' : 'R$ ' + c.value}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-slate-500">${c.user_email ? c.user_email : '<span class="text-slate-300 italic">Todos</span>'}</td>
                                <td class="px-6 py-4 text-center space-y-1">
                                    <div class="font-bold ${c.usage_count >= c.usage_limit ? 'text-rose-500' : 'text-slate-700'}">
                                        ${c.usage_count} / ${c.usage_limit}
                                    </div>
                                    ${c.expires_at ? `<div class="text-[10px] text-slate-400">Exp: ${new Date(c.expires_at).toLocaleDateString('pt-BR')}</div>` : '<div class="text-[10px] text-slate-400">Sem validade</div>'}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <button onclick="openEditCouponModal(${c.id})" class="p-1.5 mr-1 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                                        <i data-lucide="edit-2" class="w-4 h-4"></i>
                                    </button>
                                    <button onclick="deleteCoupon(${c.id})" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </td>
                            </tr>
                        `).join('');
                    }
                    lucide.createIcons();
                }
            } catch (err) {}
        }

        window.openCouponModal = () => {
            document.getElementById('addCouponForm').reset();
            document.getElementById('addCouponModal').classList.remove('hidden');
        };
        window.closeCouponModal = () => document.getElementById('addCouponModal').classList.add('hidden');
        window.closeEditCouponModal = () => document.getElementById('editCouponModal').classList.add('hidden');

        window.openEditCouponModal = (id) => {
            const c = allCoupons.find(x => x.id === id);
            if(c) {
                document.getElementById('editCouponId').value = c.id;
                document.getElementById('editCouponCode').value = c.code;
                document.getElementById('editCouponType').value = c.type;
                document.getElementById('editCouponValue').value = c.value;
                document.getElementById('editCouponLimit').value = c.usage_limit;
                document.getElementById('editCouponUserEmail').value = c.user_email || '';
                
                if (c.expires_at) {
                    // Format for datetime-local: YYYY-MM-DDThh:mm
                    document.getElementById('editCouponExpiresAt').value = c.expires_at.replace(' ', 'T');
                } else {
                    document.getElementById('editCouponExpiresAt').value = '';
                }

                document.getElementById('editCouponModal').classList.remove('hidden');
            }
        };

        document.getElementById('addCouponForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const payload = {
                code: document.getElementById('couponCode').value,
                type: document.getElementById('couponType').value,
                value: document.getElementById('couponValue').value,
                usage_limit: document.getElementById('couponLimit').value,
                user_email: document.getElementById('couponUserEmail').value,
                expires_at: document.getElementById('couponExpiresAt').value
            };
            
            try {
                const res = await fetch('../api/admin_create_coupon.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify(payload)
                });
                const result = await res.json();
                if (result.status === 'success') {
                    closeCouponModal();
                    fetchCoupons();
                } else {
                    alert('Erro: ' + result.message);
                }
            } catch (err) {
                alert('Erro ao criar cupom.');
            }
        });

        document.getElementById('editCouponForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const payload = {
                id: document.getElementById('editCouponId').value,
                type: document.getElementById('editCouponType').value,
                value: document.getElementById('editCouponValue').value,
                usage_limit: document.getElementById('editCouponLimit').value,
                user_email: document.getElementById('editCouponUserEmail').value,
                expires_at: document.getElementById('editCouponExpiresAt').value
            };
            
            try {
                const res = await fetch('../api/admin_update_coupon.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify(payload)
                });
                const result = await res.json();
                if (result.status === 'success') {
                    closeEditCouponModal();
                    fetchCoupons();
                } else {
                    alert('Erro: ' + result.message);
                }
            } catch (err) {
                alert('Erro ao atualizar cupom.');
            }
        });

        window.deleteCoupon = async (id) => {
            if(!confirm('Deseja excluir este cupom permanentemente?')) return;
            try {
                const res = await fetch('../api/admin_delete_coupon.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({id})
                });
                const result = await res.json();
                if(result.status === 'success') fetchCoupons();
                else alert('Erro: ' + result.message);
            } catch(e) {}
        };

        window.onload = () => {
            checkAdminSession();
            lucide.createIcons();
        };
    </script>
</body>
</html>
