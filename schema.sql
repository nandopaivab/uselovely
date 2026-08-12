-- ===================================================
-- Schema SQL para Banco de Dados Local (MySQL / MariaDB)
-- Projeto: useLOVELY Cosmetics
-- ===================================================

CREATE DATABASE IF NOT EXISTS `uselovely_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `uselovely_db`;

-- --------------------------------------------------------
-- Tabela `products`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `category` varchar(50) NOT NULL,
  `gender_group` varchar(50) NOT NULL,
  `gender_tag` varchar(100) NOT NULL,
  `gender_badge` varchar(100) NOT NULL,
  `tagline` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT '49.90',
  `volume` varchar(50) NOT NULL DEFAULT '236 mL / 8 fl oz',
  `image` varchar(255) NOT NULL,
  `color_theme` varchar(50) NOT NULL,
  `bg_gradient` varchar(100) NOT NULL,
  `btn_bg` varchar(100) NOT NULL,
  `shadow_class` varchar(100) NOT NULL,
  `accent_color` varchar(20) NOT NULL,
  `accent_text` varchar(50) NOT NULL,
  `notes_top` text NOT NULL,
  `notes_heart` text NOT NULL,
  `notes_base` text NOT NULL,
  `sensation` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Dados Iniciais da Tabela `products`
-- --------------------------------------------------------
INSERT INTO `products` (`id`, `name`, `category`, `gender_group`, `gender_tag`, `gender_badge`, `tagline`, `description`, `price`, `volume`, `image`, `color_theme`, `bg_gradient`, `btn_bg`, `shadow_class`, `accent_color`, `accent_text`, `notes_top`, `notes_heart`, `notes_base`, `sensation`) VALUES
('velvet-bloom', 'Velvet Bloom', 'floral', 'feminino', 'Feminino', 'bg-pink-100 text-pink-700 font-semibold', 'Floral Delicado & Aveludado', 'Uma explosão romântica de pétalas de rosa aveludadas entrelaçadas com notas doces de baunilha em flor.', 49.90, '236 mL / 8 fl oz', 'assets/images/velvet_bloom.jpg', 'rose', 'from-pink-100 via-rose-50 to-pink-200', 'bg-rose-500 hover:bg-rose-600 text-white', 'shadow-glow-rose', '#E8A5B8', 'text-rose-600', 'Frutas Vermelhas Silvestres, Peônia Rosa, Orvalho da Manhã', 'Rosas Aveludadas, Pétalas de Jasmin, Magnólia', 'Baunilha em Flor, Âmbar Cremoso, Almíscar Macio', 'Toque aveludado, feminino e levemente adocicado.'),
('purple-kiss', 'Purple Kiss', 'doce', 'feminino', 'Feminino & Envolvente', 'bg-purple-100 text-purple-700 font-semibold', 'Enchanting & Misterioso', 'Uma combinação envolvente de orquídea negra, amoras doces e um toque cremoso de madeira de cashmere.', 49.90, '236 mL / 8 fl oz', 'assets/images/purple_kiss.jpg', 'purple', 'from-purple-100 via-purple-50 to-indigo-100', 'bg-purple-600 hover:bg-purple-700 text-white', 'shadow-glow-purple', '#9B72AA', 'text-purple-600', 'Amora Preta, Orquídea Negra, Toque de Ameixa', 'Flor de Lilás, Violeta Oriental, Jasmin da Noite', 'Madeira de Cashmere, Açúcar Tostado, Âmbar Sensual', 'Misterioso, marcante e irresistivelmente doce.'),
('golden-glow', 'Golden Glow', 'ensolarado', 'masculino-unisex', 'Unisex / Compartilhável', 'bg-amber-100 text-amber-800 font-semibold', 'Luminoso & Sun-Kissed', 'A calidez radiante do sol traduzida em notas de nectarina dourada, âmbar solar e flor de laranjeira.', 49.90, '236 mL / 8 fl oz', 'assets/images/golden_glow.jpg', 'gold', 'from-amber-100 via-orange-50 to-yellow-100', 'bg-amber-600 hover:bg-amber-700 text-white', 'shadow-glow-gold', '#E3A857', 'text-amber-600', 'Nectarina Solar, Bergamota Dourada, Mandarina', 'Flor de Laranjeira, Jasmin Solar, Néctar de Pêssego', 'Âmbar Dourado, Sândalo Quente, Baunilha Tropical', 'Aconchegante, ensolarado e radiantemente iluminado.'),
('fresh-muse', 'Fresh Muse', 'fresco', 'masculino-unisex', 'Masculino & Unisex', 'bg-teal-100 text-teal-800 font-semibold', 'Refrescante & Brizante', 'A energia revitalizante da brisa marinha com flor de lótus aquática e chá verde refrescante.', 49.90, '236 mL / 8 fl oz', 'assets/images/fresh_muse.jpg', 'mint', 'from-teal-100 via-emerald-50 to-cyan-100', 'bg-teal-600 hover:bg-teal-700 text-white', 'shadow-glow-mint', '#6FB3B0', 'text-teal-600', 'Maçã Verde Crocante, Brisa Marinha, Flor de Lótus', 'Chá Verde Fresco, Lírio do Vale, Hortelã Suave', 'Musk Limpo, Cedro Branco, Pepino Aquático', 'Revigorante, ultra-fresco e energizante.'),
('midnight-pulse', 'Midnight Pulse', 'doce', 'masculino-unisex', 'Masculino & Unisex Noturno', 'bg-slate-200 text-slate-800 font-semibold', 'Sensual & Noturno', 'Uma fragrância intensa e sedutora com notas de figo escuro, íris noturna e baunilha defumada.', 49.90, '236 mL / 8 fl oz', 'assets/images/midnight_pulse.jpg', 'navy', 'from-slate-200 via-slate-100 to-blue-200', 'bg-slate-800 hover:bg-slate-900 text-white', 'shadow-glow-navy', '#3B4861', 'text-slate-800', 'Figo Escuro, Anís Estrelado, Pimenta Rosa', 'Íris Noturna, Jasmin da Meia-Noite, Orquídea', 'Baunilha Defumada, Patchouli Suave, Âmbar Profundo', 'Marcante, elegante e extremamente sedutor.');

-- --------------------------------------------------------
-- Tabela `site_config`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `site_config`;
CREATE TABLE `site_config` (
  `config_key` varchar(50) NOT NULL,
  `config_value` text NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`config_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
