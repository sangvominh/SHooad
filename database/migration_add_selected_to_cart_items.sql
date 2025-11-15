-- Migration: Add selected column to cart_items
-- Date: 2025-11-15
-- Run this if your database already exists

ALTER TABLE cart_items 
ADD COLUMN IF NOT EXISTS selected TINYINT(1) DEFAULT 0 AFTER size;

ALTER TABLE cart_items 
ADD COLUMN IF NOT EXISTS created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP AFTER selected;

ALTER TABLE cart_items 
ADD COLUMN IF NOT EXISTS updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP AFTER created_at;
