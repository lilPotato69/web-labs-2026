<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../Excursion.php';

class ExcursionIntegrationTest extends TestCase
{
    private static ?PDO $pdo = null;

    public static function setUpBeforeClass(): void
    {
        // Подключение к ТЕСТОВОЙ БД (из .env.test)
        $host = $_ENV['DB_HOST'] ?? 'db';
        $db   = $_ENV['DB_NAME'] ?? 'lab8_test_db';
        $user = $_ENV['DB_USER'] ?? 'lab8_user';
        $pass = $_ENV['DB_PASSWORD'] ?? 'lab8_pass';

        $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
        self::$pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }

    protected function setUp(): void
    {
        // Чистим таблицу перед каждым тестом
        self::$pdo->exec("DROP TABLE IF EXISTS excursions");
        (new Excursion(self::$pdo))->createTable();
    }

    public function testAddAndCount(): void
    {
        $excursion = new Excursion(self::$pdo);

        $this->assertSame(0, $excursion->count());

        $excursion->add('Ivan', '2026-10-08', 'city', 1, 'ru');
        $excursion->add('Maria', '2026-10-09', 'nature', 0, 'en');

        $this->assertSame(2, $excursion->count());
    }

    public function testGetAllReturnsInsertedRows(): void
    {
        $excursion = new Excursion(self::$pdo);
        $excursion->add('Ivan', '2026-10-08', 'city', 1, 'ru');

        $all = $excursion->getAll();

        $this->assertCount(1, $all);
        $this->assertSame('Ivan', $all[0]['name']);
        $this->assertSame('city', $all[0]['route']);
        $this->assertNotEmpty($all[0]['created_at']);
    }

    public function testFilterByAudioguide(): void
    {
        $excursion = new Excursion(self::$pdo);
        $excursion->add('Ivan', '2026-10-08', 'city', 1, 'ru');
        $excursion->add('Maria', '2026-10-09', 'nature', 0, 'en');

        $filtered = $excursion->getAll('audioguide');

        $this->assertCount(1, $filtered);
        $this->assertSame('Ivan', $filtered[0]['name']);
    }

    public function testDeleteRemovesRow(): void
    {
        $excursion = new Excursion(self::$pdo);
        $excursion->add('Ivan', '2026-10-08', 'city', 0, 'ru');

        $id = (int)self::$pdo->query("SELECT id FROM excursions LIMIT 1")->fetchColumn();
        $excursion->delete($id);

        $this->assertSame(0, $excursion->count());
    }

    // ШТРАФНОЕ: тест на ошибку с реальной БД (пустое имя → исключение)
    public function testAddThrowsExceptionForEmptyNameRealDb(): void
    {
        $excursion = new Excursion(self::$pdo);

        $this->expectException(\InvalidArgumentException::class);
        $excursion->add('', '2026-10-08', 'city', 0, 'ru');

        // Проверяем, что ничего не добавилось
        $this->assertSame(0, $excursion->count());
    }
}