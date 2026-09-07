-- ============================================================================
-- SQL DEPLOYMENT SCRIPT: ARTICLE MANAGEMENT FEATURE
-- Website: PT Abhipraya Nawasena Sejahtera (ANS)
-- Target: Hostinger phpMyAdmin (Direct execution without SSH)
-- Idempotent, safe, and dynamic batch migration tracking.
-- ============================================================================

-- 1. Create articles table
CREATE TABLE IF NOT EXISTS `articles` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title_id` VARCHAR(255) NOT NULL,
  `title_en` VARCHAR(255) NOT NULL,
  `slug_id` VARCHAR(255) NOT NULL,
  `slug_en` VARCHAR(255) NOT NULL,
  `excerpt_id` TEXT NULL DEFAULT NULL,
  `excerpt_en` TEXT NULL DEFAULT NULL,
  `content_id` LONGTEXT NULL DEFAULT NULL,
  `content_en` LONGTEXT NULL DEFAULT NULL,
  `cover_image_path` VARCHAR(255) NULL DEFAULT NULL,
  `type` VARCHAR(50) NOT NULL DEFAULT 'News',
  `published_at` DATETIME NULL DEFAULT NULL,
  `is_featured` TINYINT(1) NOT NULL DEFAULT 0,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `sort_order` INT NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `articles_slug_id_unique` (`slug_id`),
  UNIQUE KEY `articles_slug_en_unique` (`slug_en`),
  KEY `articles_type_index` (`type`),
  KEY `articles_published_at_index` (`published_at`),
  KEY `articles_is_featured_index` (`is_featured`),
  KEY `articles_is_active_index` (`is_active`),
  KEY `articles_sort_order_index` (`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Create article_product pivot table
CREATE TABLE IF NOT EXISTS `article_product` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `article_id` BIGINT UNSIGNED NOT NULL,
  `product_id` BIGINT UNSIGNED NOT NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_article_product` (`article_id`, `product_id`),
  KEY `idx_article_sort` (`article_id`, `sort_order`),
  KEY `idx_product` (`product_id`),
  CONSTRAINT `fk_ap_article` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_ap_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Dynamic Migration Tracking Registration
-- Safely inserts migration records with the current next batch number (MAX(batch) + 1).
-- Leaves existing migration records completely intact.
INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2026_09_07_100001_create_articles_table', COALESCE(MAX(`batch`), 0) + 1
FROM `migrations`
WHERE NOT EXISTS (
    SELECT 1 FROM `migrations` WHERE `migration` = '2026_09_07_100001_create_articles_table'
);

INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2026_09_07_100002_create_article_product_table', COALESCE(MAX(`batch`), 0) + 1
FROM `migrations`
WHERE NOT EXISTS (
    SELECT 1 FROM `migrations` WHERE `migration` = '2026_09_07_100002_create_article_product_table'
);
