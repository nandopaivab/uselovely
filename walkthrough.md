# Walkthrough - Banco de Dados e Autenticação 100% em MySQL (Zero Firebase)

Removemos qualquer dependência do Firebase do projeto. Todo o banco de dados (Produtos, Pedidos, Configurações) e todo o **sistema de Login e Cadastro de Usuários (Clientes e Administrador)** roda **100% no seu banco de dados MySQL local/hospedagem**.

---

## 🗄️ Tabelas Criadas no MySQL

1. **`users`**:
   - Tabela de usuários para clientes e administradores com senhas criptografadas em `password_hash()`.
   - Admin inicial criado automaticamente: `admin@uselovely.com.br` / `F3rn@nd0P190983`.
2. **`products`**:
   - Cadastro completo das 5 fragrâncias de assinatura.
3. **`orders`**:
   - Registro dos pedidos dos clientes com endereço completo (ViaCEP), itens e status de entrega.
4. **`site_config`**:
   - Armazenamento das chaves da API do Mercado Pago.

---

## 🔌 APIs REST de Autenticação em PHP (`api/`)

- **`api/auth_login.php`**: Autentica e-mail e senha no MySQL e cria a sessão PHP (`$_SESSION['user']`).
- **`api/auth_register.php`**: Cadastra novos clientes na tabela `users` do MySQL com criptografia.
- **`api/auth_check.php`**: Verifica se há uma sessão de usuário ativa no servidor PHP.
- **`api/auth_logout.php`**: Destrói a sessão ativa.

---

## 🌐 Teste ao Vivo no Servidor Local PHP

- 🛍️ **Loja Pública & Cadastro de Cliente**: [http://localhost:3000/index.php](http://localhost:3000/index.php)
- 🔑 **Painel ADM (Login MySQL)**: [http://localhost:3000/admin.php](http://localhost:3000/admin.php)

---

## 📦 Repositório GitHub Atualizado

- **URL**: [https://github.com/nandopaivab/uselovely.git](https://github.com/nandopaivab/uselovely.git)
- **Commit**: `refactor: migrate authentication and user database 100% to MySQL (zero Firebase dependency)`
- **Branch**: `main`
