<?php
/**
 * Local Database Configuration (PHP PDO)
 * Supports MySQL / MariaDB (e.g. phpMyAdmin / Herd / XAMPP) 
 * with automatic fallback to local SQLite (database.sqlite).
 */

$db_type = 'mysql'; // Configurado para MySQL de Hospedagem
$db_host = 'localhost';
$db_name = 'fernandop_uselovely';
$db_user = 'fernandop_uselovely';
$db_pass = 'F3rn@nd0P190983';

function get_db_connection() {
    global $db_type, $db_host, $db_name, $db_user, $db_pass;

    try {
        if ($db_type === 'mysql') {
            try {
                $dsn = "mysql:host={$db_host};dbname={$db_name};charset=utf8mb4";
                $pdo = new PDO($dsn, $db_user, $db_pass, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]);
            } catch (PDOException $e) {
                // If database doesn't exist yet, try creating it
                $dsn_no_db = "mysql:host={$db_host};charset=utf8mb4";
                $pdo_temp = new PDO($dsn_no_db, $db_user, $db_pass);
                $pdo_temp->exec("CREATE DATABASE IF NOT EXISTS `{$db_name}` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                
                $dsn = "mysql:host={$db_host};dbname={$db_name};charset=utf8mb4";
                $pdo = new PDO($dsn, $db_user, $db_pass, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]);
            }
        } else {
            // SQLite Local File Fallback
            $db_file = __DIR__ . '/../database.sqlite';
            $dsn = "sqlite:{$db_file}";
            $pdo = new PDO($dsn, null, null, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        }

        // Auto-initialize tables if not exist
        init_db_tables($pdo);
        return $pdo;
    } catch (PDOException $e) {
        // Fallback to SQLite if MySQL connection fails on local dev environment
        $db_file = __DIR__ . '/../database.sqlite';
        $pdo = new PDO("sqlite:{$db_file}", null, null, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
        init_db_tables($pdo);
        return $pdo;
    }
}

function init_db_tables($pdo) {
    // Create products table
    $pdo->exec("CREATE TABLE IF NOT EXISTS products (
        id VARCHAR(50) PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        category VARCHAR(50) NOT NULL,
        gender_group VARCHAR(50) NOT NULL,
        gender_tag VARCHAR(100) NOT NULL,
        gender_badge VARCHAR(100) NOT NULL,
        tagline VARCHAR(150) NOT NULL,
        description TEXT NOT NULL,
        price DECIMAL(10,2) NOT NULL DEFAULT 49.90,
        volume VARCHAR(50) NOT NULL DEFAULT '236 mL / 8 fl oz',
        image VARCHAR(255) NOT NULL,
        color_theme VARCHAR(50) NOT NULL,
        bg_gradient VARCHAR(100) NOT NULL,
        btn_bg VARCHAR(100) NOT NULL,
        shadow_class VARCHAR(100) NOT NULL,
        accent_color VARCHAR(20) NOT NULL,
        accent_text VARCHAR(50) NOT NULL,
        notes_top TEXT NOT NULL,
        notes_heart TEXT NOT NULL,
        notes_base TEXT NOT NULL,
        sensation TEXT NOT NULL,
        olfactory_reference VARCHAR(255) NOT NULL DEFAULT '',
        stock_quantity INT NOT NULL DEFAULT 100,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // Migration Check for olfactory_reference column
    try {
        $pdo->query("SELECT olfactory_reference FROM products LIMIT 1");
    } catch (Exception $e) {
        try {
            $pdo->exec("ALTER TABLE products ADD COLUMN olfactory_reference VARCHAR(255) NOT NULL DEFAULT ''");
        } catch (Exception $ex) {}
    }

    // Migration Check for stock_quantity column
    try {
        $pdo->query("SELECT stock_quantity FROM products LIMIT 1");
    } catch (Exception $e) {
        try {
            $pdo->exec("ALTER TABLE products ADD COLUMN stock_quantity INT NOT NULL DEFAULT 100");
        } catch (Exception $ex) {}
    }

    // Create site_config table
    $pdo->exec("CREATE TABLE IF NOT EXISTS site_config (
        config_key VARCHAR(50) PRIMARY KEY,
        config_value TEXT NOT NULL,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // Create orders table with Mercado Pago & Stock Idempotency fields
    $pdo->exec("CREATE TABLE IF NOT EXISTS orders (
        id VARCHAR(50) PRIMARY KEY,
        order_number VARCHAR(50) NOT NULL,
        customer_name VARCHAR(100) NOT NULL,
        customer_email VARCHAR(100) NOT NULL,
        customer_phone VARCHAR(50) NOT NULL,
        customer_cpf VARCHAR(20) NOT NULL DEFAULT '',
        shipping_address TEXT NOT NULL,
        items TEXT NOT NULL,
        subtotal DECIMAL(10,2) NOT NULL,
        shipping_amount DECIMAL(10,2) NOT NULL DEFAULT 0.00,
        discount_amount DECIMAL(10,2) NOT NULL DEFAULT 0.00,
        total_amount DECIMAL(10,2) NOT NULL,
        payment_method VARCHAR(50) NOT NULL DEFAULT 'Mercado Pago',
        payment_status VARCHAR(50) NOT NULL DEFAULT 'pending',
        order_status VARCHAR(50) NOT NULL DEFAULT 'awaiting_payment',
        mercado_pago_preference_id VARCHAR(100) DEFAULT '',
        mercado_pago_payment_id VARCHAR(100) DEFAULT '',
        external_reference VARCHAR(100) DEFAULT '',
        stock_reduced TINYINT(1) DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // Migration Check: If old SQLite/MySQL table lacks order_number, recreate or alter
    try {
        $pdo->query("SELECT order_number FROM orders LIMIT 1");
    } catch (Exception $e) {
        $pdo->exec("DROP TABLE IF EXISTS orders");
        $pdo->exec("CREATE TABLE orders (
            id VARCHAR(50) PRIMARY KEY,
            order_number VARCHAR(50) NOT NULL,
            customer_name VARCHAR(100) NOT NULL,
            customer_email VARCHAR(100) NOT NULL,
            customer_phone VARCHAR(50) NOT NULL,
            customer_cpf VARCHAR(20) NOT NULL DEFAULT '',
            shipping_address TEXT NOT NULL,
            items TEXT NOT NULL,
            subtotal DECIMAL(10,2) NOT NULL,
            shipping_amount DECIMAL(10,2) NOT NULL DEFAULT 0.00,
            discount_amount DECIMAL(10,2) NOT NULL DEFAULT 0.00,
            total_amount DECIMAL(10,2) NOT NULL,
            payment_method VARCHAR(50) NOT NULL DEFAULT 'Mercado Pago',
            payment_status VARCHAR(50) NOT NULL DEFAULT 'pending',
            order_status VARCHAR(50) NOT NULL DEFAULT 'awaiting_payment',
            mercado_pago_preference_id VARCHAR(100) DEFAULT '',
            mercado_pago_payment_id VARCHAR(100) DEFAULT '',
            external_reference VARCHAR(100) DEFAULT '',
            stock_reduced TINYINT(1) DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");
    }

    // Create users table for 100% MySQL authentication
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        phone VARCHAR(50) DEFAULT '',
        cpf VARCHAR(20) DEFAULT '',
        role VARCHAR(20) NOT NULL DEFAULT 'customer',
        reset_token VARCHAR(100) DEFAULT NULL,
        reset_expires DATETIME DEFAULT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // Migration Check for users table columns
    try {
        $pdo->query("SELECT cpf FROM users LIMIT 1");
    } catch (Exception $e) {
        try { $pdo->exec("ALTER TABLE users ADD COLUMN cpf VARCHAR(20) DEFAULT ''"); } catch (Exception $ex) {}
    }
    try {
        $pdo->query("SELECT reset_token FROM users LIMIT 1");
    } catch (Exception $e) {
        try { $pdo->exec("ALTER TABLE users ADD COLUMN reset_token VARCHAR(100) DEFAULT NULL"); } catch (Exception $ex) {}
        try { $pdo->exec("ALTER TABLE users ADD COLUMN reset_expires DATETIME DEFAULT NULL"); } catch (Exception $ex) {}
    }

    // Create user_addresses table for multiple saved delivery addresses
    $pdo->exec("CREATE TABLE IF NOT EXISTS user_addresses (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        recipient_name VARCHAR(100) NOT NULL,
        cep VARCHAR(20) NOT NULL,
        street VARCHAR(150) NOT NULL,
        number VARCHAR(30) NOT NULL,
        complement VARCHAR(100) DEFAULT '',
        neighborhood VARCHAR(100) NOT NULL,
        city VARCHAR(100) NOT NULL,
        state VARCHAR(10) NOT NULL,
        is_default TINYINT(1) DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // Seed default admin user if not exists
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE role = 'admin'");
    $stmt->execute();
    if ($stmt->fetchColumn() == 0) {
        $adminPass = password_hash('F3rn@nd0P190983', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, phone, role) VALUES ('Administrador', 'admin@uselovely.com.br', :pass, '(11) 99999-9999', 'admin')");
        $stmt->execute([':pass' => $adminPass]);
    }

    // Check if products table has data, if not seed default 5 products
    $stmt = $pdo->query("SELECT COUNT(*) FROM products");
    if ($stmt->fetchColumn() == 0) {
        seed_default_products($pdo);
    }
}

function seed_default_products($pdo) {
    $products = [
        [
            'id' => 'velvet-bloom',
            'name' => 'Velvet Bloom',
            'category' => 'floral',
            'gender_group' => 'feminino',
            'gender_tag' => 'Feminino',
            'gender_badge' => 'bg-pink-100 text-pink-700 font-semibold',
            'tagline' => 'Floral Gourmand & Elegante',
            'description' => 'Floral gourmand, feminino, doce e elegante. Com notas de íris, flores brancas, baunilha, praliné e fundo envolvente.',
            'price' => 49.90,
            'volume' => '236 mL / 8 fl oz',
            'image' => 'assets/images/velvet_bloom.jpg',
            'color_theme' => 'rose',
            'bg_gradient' => 'from-pink-100 via-rose-50 to-pink-200',
            'btn_bg' => 'bg-rose-500 hover:bg-rose-600 text-white',
            'shadow_class' => 'shadow-glow-rose',
            'accent_color' => '#E8A5B8',
            'accent_text' => 'text-rose-600',
            'notes_top' => 'Flores Brancas & Cítricos Leves',
            'notes_heart' => 'Íris & Praliné',
            'notes_base' => 'Baunilha Envolvente & Patchouli',
            'sensation' => 'Doce, elegante e aveludada.',
            'olfactory_reference' => 'La Vie Est Belle (Lancôme)'
        ],
        [
            'id' => 'purple-kiss',
            'name' => 'Purple Kiss',
            'category' => 'doce',
            'gender_group' => 'feminino',
            'gender_tag' => 'Feminino & Envolvente',
            'gender_badge' => 'bg-purple-100 text-purple-700 font-semibold',
            'tagline' => 'Oriental Gourmand & Sensual',
            'description' => 'Oriental gourmand, sensual e marcante. Com notas de amêndoa, flores brancas, baunilha e acordes cremosos.',
            'price' => 49.90,
            'volume' => '236 mL / 8 fl oz',
            'image' => 'assets/images/purple_kiss.jpg',
            'color_theme' => 'purple',
            'bg_gradient' => 'from-purple-100 via-purple-50 to-indigo-100',
            'btn_bg' => 'bg-purple-600 hover:bg-purple-700 text-white',
            'shadow_class' => 'shadow-glow-purple',
            'accent_color' => '#9B72AA',
            'accent_text' => 'text-purple-600',
            'notes_top' => 'Amêndoa Doce & Ameixa',
            'notes_heart' => 'Jasmin & Flores Brancas',
            'notes_base' => 'Baunilha Cremosa & Fava Tonka',
            'sensation' => 'Sensual, marcante e aveludada.',
            'olfactory_reference' => 'Hypnotic Poison (Dior)'
        ],
        [
            'id' => 'golden-glow',
            'name' => 'Golden Glow',
            'category' => 'ensolarado',
            'gender_group' => 'masculino-unisex',
            'gender_tag' => 'Unisex / Compartilhável',
            'gender_badge' => 'bg-amber-100 text-amber-800 font-semibold',
            'tagline' => 'Frutado Intenso & Sofisticado',
            'description' => 'Frutado intenso e sofisticado. Frutas cítricas e doces, musk, âmbar e baunilha com bastante presença.',
            'price' => 49.90,
            'volume' => '236 mL / 8 fl oz',
            'image' => 'assets/images/golden_glow.jpg',
            'color_theme' => 'gold',
            'bg_gradient' => 'from-amber-100 via-orange-50 to-yellow-100',
            'btn_bg' => 'bg-amber-600 hover:bg-amber-700 text-white',
            'shadow_class' => 'shadow-glow-gold',
            'accent_color' => '#E3A857',
            'accent_text' => 'text-amber-600',
            'notes_top' => 'Laranja da Sicília & Limão',
            'notes_heart' => 'Cesto de Frutas Mediterrâneas',
            'notes_base' => 'Musk Branco, Âmbar & Baunilha',
            'sensation' => 'Ensolarada, marcante e sofisticada.',
            'olfactory_reference' => 'Erba Pura (Xerjoff)'
        ],
        [
            'id' => 'fresh-muse',
            'name' => 'Fresh Muse',
            'category' => 'fresco',
            'gender_group' => 'masculino-unisex',
            'gender_tag' => 'Masculino & Unisex',
            'gender_badge' => 'bg-teal-100 text-teal-800 font-semibold',
            'tagline' => 'Floral Frutado Fresco & Limpo',
            'description' => 'Floral frutado fresco, limpo e elegante. Cítricos, flores delicadas e musk. Ótimo para uso diário.',
            'price' => 49.90,
            'volume' => '236 mL / 8 fl oz',
            'image' => 'assets/images/fresh_muse.jpg',
            'color_theme' => 'mint',
            'bg_gradient' => 'from-teal-100 via-emerald-50 to-cyan-100',
            'btn_bg' => 'bg-teal-600 hover:bg-teal-700 text-white',
            'shadow_class' => 'shadow-glow-mint',
            'accent_color' => '#6FB3B0',
            'accent_text' => 'text-teal-600',
            'notes_top' => 'Toranja & Cítricos Frescos',
            'notes_heart' => 'Jasmin Delicado & Íris',
            'notes_base' => 'Musk Limpo & Cedro',
            'sensation' => 'Frescor elegante e revitalizante.',
            'olfactory_reference' => 'Chance (Chanel)'
        ],
        [
            'id' => 'midnight-pulse',
            'name' => 'Midnight Pulse',
            'category' => 'doce',
            'gender_group' => 'masculino-unisex',
            'gender_tag' => 'Masculino & Unisex Noturno',
            'gender_badge' => 'bg-slate-200 text-slate-800 font-semibold',
            'tagline' => 'Amadeirado Aromático Masculino',
            'description' => 'Amadeirado aromático masculino. Cítrico fresco na saída, especiarias, madeiras nobres e fundo sofisticado.',
            'price' => 49.90,
            'volume' => '236 mL / 8 fl oz',
            'image' => 'assets/images/midnight_pulse.jpg',
            'color_theme' => 'navy',
            'bg_gradient' => 'from-slate-200 via-slate-100 to-blue-200',
            'btn_bg' => 'bg-slate-800 hover:bg-slate-900 text-white',
            'shadow_class' => 'shadow-glow-navy',
            'accent_color' => '#3B4861',
            'accent_text' => 'text-slate-800',
            'notes_top' => 'Limão Siciliano, Hortelã & Bergamota',
            'notes_heart' => 'Gengibre, Noz-Moscada & Especiarias',
            'notes_base' => 'Incenso, Cedro & Sândalo',
            'sensation' => 'Masculina, marcante e magnética.',
            'olfactory_reference' => 'Bleu de Chanel (Chanel)'
        ]
    ];

    $sql = "INSERT INTO products (
        id, name, category, gender_group, gender_tag, gender_badge, tagline, description, price, volume, image,
        color_theme, bg_gradient, btn_bg, shadow_class, accent_color, accent_text, notes_top, notes_heart, notes_base, sensation, olfactory_reference
    ) VALUES (
        :id, :name, :category, :gender_group, :gender_tag, :gender_badge, :tagline, :description, :price, :volume, :image,
        :color_theme, :bg_gradient, :btn_bg, :shadow_class, :accent_color, :accent_text, :notes_top, :notes_heart, :notes_base, :sensation, :olfactory_reference
    )";

    $stmt = $pdo->prepare($sql);
    foreach ($products as $p) {
        $stmt->execute([
            ':id' => $p['id'],
            ':name' => $p['name'],
            ':category' => $p['category'],
            ':gender_group' => $p['gender_group'],
            ':gender_tag' => $p['gender_tag'],
            ':gender_badge' => $p['gender_badge'],
            ':tagline' => $p['tagline'],
            ':description' => $p['description'],
            ':price' => $p['price'],
            ':volume' => $p['volume'],
            ':image' => $p['image'],
            ':color_theme' => $p['color_theme'],
            ':bg_gradient' => $p['bg_gradient'],
            ':btn_bg' => $p['btn_bg'],
            ':shadow_class' => $p['shadow_class'],
            ':accent_color' => $p['accent_color'],
            ':accent_text' => $p['accent_text'],
            ':notes_top' => $p['notes_top'],
            ':notes_heart' => $p['notes_heart'],
            ':notes_base' => $p['notes_base'],
            ':sensation' => $p['sensation'],
            ':olfactory_reference' => $p['olfactory_reference']
        ]);
    }
}
