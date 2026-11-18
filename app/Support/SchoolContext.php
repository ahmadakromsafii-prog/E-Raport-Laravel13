<?php
namespace App\Support;
final class SchoolContext {
 private static ?string $level=null;
 private static bool $superAdmin=false;
 public static function reset(): void { self::$level=null; self::$superAdmin=false; }
 public static function set(?string $level,bool $superAdmin=false): void { self::$level=$level; self::$superAdmin=$superAdmin; }
 public static function level(): ?string { return self::$level; }
 public static function isSuperAdmin(): bool { return self::$superAdmin; }
}
