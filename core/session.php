<?php
namespace Core;

class Session
{
    private static ?Session $instance = null;

    private function __construct()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            if (getenv("APP_ENV") === "development") {
                session_name('dev');
            }
            session_start();
        }
    }

    public static function start(): void
    {
        self::getInstance();
    }

    private static function getInstance(): Session
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public static function set(string $name, mixed $value): void
    {
        self::getInstance();
        $_SESSION[$name] = $value;
    }

    public static function get(string $name, mixed $case = ''): mixed
    {
        self::getInstance();
        return ($_SESSION[$name] ?? $case);
    }

    public static function delete(string $name): void
    {
        self::getInstance();
        unset($_SESSION[$name]);
    }

    public static function destroy(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
            $_SESSION = [];
            self::$instance = null;
        }
    }

    public static function getAll(): array
    {
        self::getInstance();
        return $_SESSION;
    }

    public static function isActive(): bool
    {
        return session_status() === PHP_SESSION_ACTIVE;
    }
}
