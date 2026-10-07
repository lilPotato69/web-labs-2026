<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../Excursion.php';

class ExcursionTest extends TestCase
{
    private $pdoMock;
    private $stmtMock;

    protected function setUp(): void
    {
        // Мокаем PDOStatement
        $this->stmtMock = $this->getMockBuilder(PDOStatement::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['execute', 'fetchAll', 'fetchColumn'])
            ->getMock();

        // Мокаем PDO
        $this->pdoMock = $this->getMockBuilder(PDO::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['prepare', 'query', 'exec'])
            ->getMock();
    }

    // --- 1. Unit-тест: успешное добавление ---
    public function testAddCallsExecuteWithCorrectParams(): void
    {
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->willReturn($this->stmtMock);

        $this->stmtMock->expects($this->once())
            ->method('execute')
            ->with(['Ivan', '2026-10-08', 'city', 1, 'ru'])
            ->willReturn(true);

        $excursion = new Excursion($this->pdoMock);
        $excursion->add('Ivan', '2026-10-08', 'city', 1, 'ru');

        $this->assertTrue(true); // если дошли — тест прошёл
    }

    // --- 2. Unit-тест: getAll возвращает массив ---
    public function testGetAllReturnsArray(): void
    {
        $expected = [
            ['id' => 1, 'name' => 'Ivan'],
            ['id' => 2, 'name' => 'Maria'],
        ];

        $this->stmtMock->method('fetchAll')->willReturn($expected);
        $this->pdoMock->method('query')->willReturn($this->stmtMock);

        $excursion = new Excursion($this->pdoMock);
        $result = $excursion->getAll();

        $this->assertIsArray($result);
        $this->assertCount(2, $result);
        $this->assertEquals('Ivan', $result[0]['name']);
    }

    // --- 3. Тест с mock: count возвращает число ---
    public function testCountReturnsNumber(): void
    {
        $this->stmtMock->method('fetchColumn')->willReturn('5');
        $this->pdoMock->method('query')->willReturn($this->stmtMock);

        $excursion = new Excursion($this->pdoMock);
        $this->assertSame(5, $excursion->count());
    }

    // --- 4. ШТРАФНОЕ: тест на ошибку (пустое имя) ---
    public function testAddThrowsExceptionForEmptyName(): void
    {
        $excursion = new Excursion($this->pdoMock);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Имя не может быть пустым');

        $excursion->add('', '2026-10-08', 'city', 0, 'ru');
    }

    // --- 5. ШТРАФНОЕ: тест на слишком длинное имя ---
    public function testAddThrowsExceptionForTooLongName(): void
    {
        $excursion = new Excursion($this->pdoMock);

        $this->expectException(\InvalidArgumentException::class);
        $excursion->add(str_repeat('a', 101), '2026-10-08', 'city', 0, 'ru');
    }
}