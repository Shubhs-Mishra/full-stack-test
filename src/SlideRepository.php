<?php

require_once __DIR__ . '/Database.php';

class SlideRepository
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = Database::getConnection();
    }

    public function fetchTopics(): array
    {
        $query = 'SELECT DISTINCT topic FROM slides ORDER BY topic';
        return $this->connection->query($query)->fetchAll();
    }

    public function fetchSlidesByTopic(string $topic): array
    {
        $sql = 'SELECT * FROM slides WHERE topic = :topic ORDER BY sort_order, id';
        $statement = $this->connection->prepare($sql);
        $statement->execute(['topic' => $topic]);
        return $statement->fetchAll();
    }

    public function fetchSlideById(int $slideId): ?array
    {
        $sql = 'SELECT * FROM slides WHERE id = :id';
        $statement = $this->connection->prepare($sql);
        $statement->execute(['id' => $slideId]);

        $slide = $statement->fetch();
        return $slide === false ? null : $slide;
    }

    public function fetchAllSlides(): array
    {
        $sql = 'SELECT * FROM slides ORDER BY sort_order ASC, topic ASC, id ASC';
        return $this->connection->query($sql)->fetchAll();
    }

    public function saveSlide(array $slideData): bool
    {
        if (isset($slideData['id']) && $this->fetchSlideById((int) $slideData['id'])) {
            return $this->updateSlide($slideData);
        }

        return $this->createSlide($slideData);
    }

    private function createSlide(array $slideData): bool
    {
        $sql = 'INSERT INTO slides (topic, title, image_path, sort_order) VALUES (:topic, :title, :image_path, :sort_order)';
        $statement = $this->connection->prepare($sql);
        return $statement->execute([
            'topic' => trim($slideData['topic']),
            'title' => trim($slideData['title']),
            'image_path' => trim($slideData['image_path']),
            'sort_order' => (int) $slideData['sort_order'],
        ]);
    }

    private function updateSlide(array $slideData): bool
    {
        $sql = 'UPDATE slides SET topic = :topic, title = :title, image_path = :image_path, sort_order = :sort_order WHERE id = :id';
        $statement = $this->connection->prepare($sql);
        return $statement->execute([
            'topic' => trim($slideData['topic']),
            'title' => trim($slideData['title']),
            'image_path' => trim($slideData['image_path']),
            'sort_order' => (int) $slideData['sort_order'],
            'id' => (int) $slideData['id'],
        ]);
    }

    public function removeSlide(int $slideId): bool
    {
        $sql = 'DELETE FROM slides WHERE id = :id';
        $statement = $this->connection->prepare($sql);
        return $statement->execute(['id' => $slideId]);
    }
}
