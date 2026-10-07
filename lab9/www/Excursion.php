<?php

class Excursion
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function createTable(): void
    {
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

    public function add(string $name, string $date, string $route, int $audioguide, string $language): void
    {
        if (trim($name) === '') {
            throw new \InvalidArgumentException('Имя не может быть пустым');
        }
        if (mb_strlen($name) > 100) {
            throw new \InvalidArgumentException('Имя слишком длинное');
        }

        $stmt = $this->pdo->prepare(
            "INSERT INTO excursions (name, date, route, audioguide, language) VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->execute([$name, $date, $route, $audioguide, $language]);
    }

    public function getAll(string $filter = 'all'): array
    {
        $sql = "SELECT * FROM excursions";
        if ($filter === 'audioguide') {
            $sql .= " WHERE audioguide = 1";
        }
        $sql .= " ORDER BY created_at DESC";

        return $this->pdo->query($sql)->fetchAll();
    }

    public function count(): int
    {
        return (int)$this->pdo->query("SELECT COUNT(*) FROM excursions")->fetchColumn();
    }

    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM excursions WHERE id = ?");
        $stmt->execute([$id]);
    }
}