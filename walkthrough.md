# Walkthrough - Cadastro de Clientes, Endereço de Entrega (ViaCEP) & Pedidos Online

Implementamos e disponibilizamos o fluxo completo de **Vendas Online com Cadastro de Clientes, Busca de CEP Automática via ViaCEP, Checkout e Gerenciamento de Pedidos**.

---

## 🛍️ Recursos Implementados

1. **Central da Conta do Cliente ("Entrar / Criar Conta")**:
   - Modal com abas para **Login** e **Criar Conta (Cadastro)** conectado ao Firebase Auth.
   - O cabeçalho exibe o estado do usuário logado e botão para acessar a central **"Meus Pedidos"**.

2. **Endereço de Entrega Inteligente (ViaCEP)**:
   - Digitação de CEP com preenchimento automático de Rua/Logradouro, Bairro, Cidade e Estado (UF) via integração com a API **ViaCEP** (`viacep.com.br`).
   - Campos complementares para Número, Apto/Complemento e Ponto de Referência.

3. **Checkout Completo & Gravação de Pedidos (`api/create_order.php`)**:
   - Ao finalizar a compra pelo checkout (via **PIX com 5% OFF** ou **Cartão de Crédito em até 6x**), o pedido ganha um código único (ex: `#LV-84920`) e é salvo na tabela `orders` do seu banco de dados local (MySQL / SQLite).
   - Limpa a sacola e exibe confirmação instantânea com atalho para a área do cliente.

4. **"Meus Pedidos" (Histórico do Cliente)**:
   - Permite que o cliente logado consulte todos os seus pedidos já realizados, acompanhe o status de entrega (`Em Separação`, `Enviado / Em Trânsito`, `Entregue`) e o endereço informado.

5. **Gerenciador de Pedidos no Painel ADM (`admin.php`)**:
   - Nova tabela em `admin.php` para os administradores visualizarem todos os pedidos realizados no e-commerce e **atualizarem o status de envio em tempo real**.

---

## 🌐 Como Testar no Servidor Local

- 🛍️ **Loja Pública & Checkout**: [http://localhost:3000/index.php](http://localhost:3000/index.php)
- 🔑 **Painel ADM & Gestão de Pedidos**: [http://localhost:3000/admin.php](http://localhost:3000/admin.php)

---

## 📦 Repositório GitHub Atualizado

- **URL**: [https://github.com/nandopaivab/uselovely.git](https://github.com/nandopaivab/uselovely.git)
- **Commit**: `feat: add customer account creation, ViaCEP address lookup, online checkout, and admin order tracking`
- **Branch**: `main`
