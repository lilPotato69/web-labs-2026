<?php
class Excursion {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
        $this->createTable();
    }

    // Штрафное задание 1: created_at
    private function createTable(): void {
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS excursions (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(100) NOT NULL,
                date DATE NOT NULL,
                route VARCHAR(50) NOT NULL,
                audioguide TINYINT(1) DEFAULT 0,
                language VARCHAR(10) DEFAULT 'ru',
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ");
    }

    public function add(string $name, string $date, string $route, int $audioguide, string $language): void {
        $stmt = $this->pdo->prepare(
            "INSERT INTO excursions (name, date, route, audioguide, language) VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->execute([$name, $date, $route, $audioguide, $language]);
    }

    // Штрафное задание 2: сортировка по дате создания (новые сверху)
    // Штрафное задание 3: фильтр (например, только с аудиогидом)
    public function getAll(string $filter = 'all'): array {
        $sql = "SELECT * FROM excursions";
        $params = [];

        if ($filter === 'audioguide') {
            $sql .= " WHERE audioguide = 1";
        }

        $sql .= " ORDER BY created_at DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function delete(int $id): void {
        $stmt = $this->pdo->prepare("DELETE FROM excursions WHERE id = ?");
        $stmt->execute([$id]);
    }

    // Штрафное задание 4: статистика
    public function getStats(): array {
        $stats = [];

        $stats['total'] = (int)$this->pdo->query("SELECT COUNT(*) FROM excursions")->fetchColumn();
        $stats['with_audioguide'] = (int)$this->pdo->query("SELECT COUNT(*) FROM excursions WHERE audioguide = 1")->fetchColumn();
        $stats['in_english'] = (int)$this->pdo->query("SELECT COUNT(*) FROM excursions WHERE language = 'en'")->fetchColumn();
        $stats['today'] = (int)$this->pdo->query("SELECT COUNT(*) FROM excursions WHERE DATE(created_at) = CURDATE()")->fetchColumn();

        return $stats;
    }
}