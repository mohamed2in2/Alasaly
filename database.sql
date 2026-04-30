-- database.sql
-- Run this script once in phpMyAdmin or any MySQL/MariaDB client.
-- It creates the database (if it doesn't exist) and the access_codes table.

CREATE DATABASE IF NOT EXISTS school_platform
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE school_platform;

CREATE TABLE IF NOT EXISTS access_codes (
    id         INT           NOT NULL AUTO_INCREMENT,
    code       VARCHAR(50)   NOT NULL,
    video_id   VARCHAR(255)  NOT NULL,
    is_used    TINYINT(1)    NOT NULL DEFAULT 0,
    created_at TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    used_at    TIMESTAMP     NULL     DEFAULT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_code (code)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
