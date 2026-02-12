<?php

declare(strict_types=1);

namespace TheFileHub\Models;

use TheFileHub\Core\Database;

class Post extends AbstractModel
{
    protected static ?string $primaryKey = 'idPost';

    public ?int $idPost = null;

    public ?int $idCreator = null {
        set => $this->idCreator = $value;
    }

    public ?string $name = null {
        get => $this->name;
        set => $this->name = $value;
    }

    public ?string $fileLink = null {
        set => $this->fileLink = $value;
    }

    public ?string $description = null {
        set => $this->description = $value;
    }

    public ?int $likes = null {
        set => $this->likes = $value;
    }

    public ?int $dislikes = null {
        set => $this->dislikes = $value;
    }

    public ?string $createdAt = null {
        set => $this->createdAt = $value;
    }

    protected array $casts = [];

    public string $fileExt = '';

    public function getPost(): int
    {
        return $this->idPost;
    }

    public function getCreator(): int
    {
        return $this->idCreator;
    }

    public function setCreator(int $idCreator): void
    {
        $this->idCreator = $idCreator;
    }

    public function getFileLink(): string
    {
        return $this->fileLink;
    }

    public function setFileLink(string $fileLink): void
    {
        $this->fileLink = $fileLink;
    }

    public function getDescription(): string
    {
        return $this->description;
    }
    
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getLikes(): int
    {
        return $this->likes;
    }

    public function setLikes(int $likes): void
    {
        $this->likes = $likes;
    }

    public function getDislikes(): int
    {
        return $this->dislikes;
    }

    public function setDislikes(int $dislikes): void
    {
        $this->dislikes = $dislikes;
    }

    public function getFileExt(): string
    {
        return $this->fileExt;
    }

    public function setFileExt(string $fileExt): void
    {
        $this->fileExt = $fileExt;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public static function All(): array
    {
        $db = Database::connection();

        $query = "SELECT * FROM Posts";

        $stmt = $db->prepare($query);

        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            foreach ($stmt->fetchAll() as $row) {
                $posts[] = new self()->fill([
                    'idPost' => $row['idPost'],
                    'idCreator' => $row['idCreator'],
                    'name' => $row['name'],
                    'fileLink' => $row['fileLink'],
                    'description' => $row['description'],
                    'likes' => $row['likes'],
                    'dislikes' => $row['dislikes']
                ]);
            }
        }

        return $posts ?? [];
    }

    public static function findById(int $idPost): Post|null
    {
        $db = Database::connection();

        $query = "SELECT * FROM Posts WHERE idPost = :idPost";

        $stmt = $db->prepare($query);

        $stmt->bindValue(':idPost', $idPost);

        $stmt->execute();

        $row = $stmt->fetch();

        return $row ? new self()->fill($row) : null;
    }

    public static function findByCreator(string $idCreator): Post|null
    {
        $db = Database::connection();

        $query = "SELECT * FROM Posts WHERE idCreator = :idCreator";

        $stmt = $db->prepare($query);

        $stmt->bindValue(':idCreator', $idCreator);

        $stmt->execute();

        $row = $stmt->fetch();

        return $row ? new self()->fill($row) : null;
    }

    public function insert(): bool
    {
        $db = Database::connection();

        $query = "INSERT INTO Posts (idCreator, name, fileLink, description, likes, dislikes) VALUES (:idCreator, :name, :fileLink, :description, :likes, :dislikes)";

        $stmt = $db->prepare($query);

        $stmt->bindValue(':idCreator', $this->idCreator);
        $stmt->bindValue(':name', $this->name);
        $stmt->bindValue(':fileLink', $this->fileLink);
        $stmt->bindValue(':description', $this->description);
        $stmt->bindValue(':likes', $this->likes);
        $stmt->bindValue(':dislikes', $this->dislikes);

        $success = $stmt->execute();

        if ($success) {
            $this->idPost = (int) Database::connection()->lastInsertId();
        }

        return $success;
    }

    public function update(): bool
    {
        $db = Database::connection();

        $query = "UPDATE Posts SET idCreator = :idCreator, name = :name, fileLink = :fileLink, description = :description, likes = :likes, dislikes = :dislikes WHERE idPost = :idPost";

        $stmt = $db->prepare($query);

        $stmt->bindValue(':idCreator', $this->idCreator);
        $stmt->bindValue(':name', $this->name);
        $stmt->bindValue(':fileLink', $this->fileLink);
        $stmt->bindValue(':description', $this->description);
        $stmt->bindValue(':likes', $this->likes);
        $stmt->bindValue(':dislikes', $this->dislikes);

        $success = $stmt->execute();

        return $success;
    }

    public function delete(): bool
    {
        $db = Database::connection();

        $query = "DELETE FROM Posts WHERE idPost = :idPost";

        $stmt = $db->prepare($query);

        $stmt->bindValue(':idPost', $this->findById($this->idPost)->idPost);

        return $stmt->execute();
    }
}