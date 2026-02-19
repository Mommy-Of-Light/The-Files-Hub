<?php

declare(strict_types=1);

namespace TheFileHub\Models;

use Slim\Psr7\Response;
use TheFileHub\Core\Database;
use TheFileHub\Services\UserService;

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
    public int $action = -1;

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

    public function getAction(): int
    {
        $db = Database::connection();
        $check = $db->prepare("SELECT action FROM PostsUsers WHERE idPosts = :idPost AND idUser = :idUser");
        $check->bindValue(':idPost', $this->idPost);
        $check->bindValue(':idUser', UserService::current()->getIdUser());
        $check->execute();
        $data = $check->fetch();

        return $data ? (int) $data['action'] : -1;
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

        $query = "SELECT * FROM Posts 
                WHERE idPost = :idPost";

        $stmt = $db->prepare($query);

        $stmt->bindValue(':idPost', $idPost);

        $stmt->execute();

        $row = $stmt->fetch();

        return $row ? new self()->fill($row) : null;
    }

    public static function findByCreator(string $idCreator): Post|null
    {
        $db = Database::connection();

        $query = "SELECT * FROM Posts 
                WHERE idCreator = :idCreator";

        $stmt = $db->prepare($query);

        $stmt->bindValue(':idCreator', $idCreator);

        $stmt->execute();

        $row = $stmt->fetch();

        return $row ? new self()->fill($row) : null;
    }

    public function modifyLikes($response, string $type): Response
    {
        $db = Database::connection();

        $userId = UserService::current()->getIdUser();

        $check = $db->prepare("SELECT * FROM PostsUsers WHERE idPosts = :idPost AND idUser = :idUser");
        $check->bindValue(':idPost', $this->idPost);
        $check->bindValue(':idUser', $userId);
        $check->execute();
        $data = $check->fetchAll();

        $creator = User::findById($this->getCreator());

        foreach ($data as $row) {
            if ($row['action'] === 1 && $type === 'like') {
                $_SESSION['error'] = 'Vous avez déjà aimé ce post.';
                return $response->withHeader('Location', "/post/single/{$this->idPost}")->withStatus(302);
            } elseif ($row['action'] === 0 && $type === 'dislike') {
                $_SESSION['error'] = 'Vous avez déjà disliké ce post.';
                return $response->withHeader('Location', "/post/single/{$this->idPost}")->withStatus(302);
            } elseif ($row['action'] === 1 && $type === 'dislike') {
                $update = $db->prepare("UPDATE PostsUsers SET action = 0 WHERE idPosts = :idPost AND idUser = :idUser");
                $update->bindValue(':idPost', $this->idPost);
                $update->bindValue(':idUser', $userId);
                $update->execute();
                $this->setLikes($this->getLikes() - 1);
                $creator->setXp_Add(-5);
            } elseif ($row['action'] === 0 && $type === 'like') {
                $update = $db->prepare("UPDATE PostsUsers SET action = 1 WHERE idPosts = :idPost AND idUser = :idUser");
                $update->bindValue(':idPost', $this->idPost);
                $update->bindValue(':idUser', $userId);
                $update->execute();
                $this->setDislikes($this->getDislikes() - 1);
                $creator->setXp_Add(20);
            }
        }

        $creator->update();

        return $response;
    }

    public function insert(): bool
    {
        $db = Database::connection();

        $query = "INSERT INTO Posts (idCreator, name, fileLink, description, likes, dislikes) 
                VALUES (:idCreator, :name, :fileLink, :description, :likes, :dislikes)";

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

        try {
            $db->beginTransaction();

            $updateQuery = "UPDATE Posts 
                        SET idCreator = :idCreator, 
                            name = :name, 
                            fileLink = :fileLink, 
                            description = :description, 
                            likes = :likes, 
                            dislikes = :dislikes 
                        WHERE idPost = :idPost";

            $updateStmt = $db->prepare($updateQuery);

            $updateStmt->bindValue(':idCreator', $this->idCreator);
            $updateStmt->bindValue(':name', $this->name);
            $updateStmt->bindValue(':fileLink', $this->fileLink);
            $updateStmt->bindValue(':description', $this->description);
            $updateStmt->bindValue(':likes', $this->likes);
            $updateStmt->bindValue(':dislikes', $this->dislikes);
            $updateStmt->bindValue(':idPost', $this->idPost);

            $updateStmt->execute();

            $check = $db->prepare("SELECT * FROM PostsUsers WHERE idPosts = :idPost AND idUser = :idUser");
            $check->bindValue(':idPost', $this->idPost);
            $check->bindValue(':idUser', UserService::current()->getIdUser());
            $check->execute();

            $insertQuery = "INSERT INTO PostsUsers (idPosts, idUser, action) 
                        VALUES (:idPosts, :idUser, :action)";

            if ($check->rowCount() > 0) {
                $insertQuery = "UPDATE PostsUsers SET action = :action WHERE idPosts = :idPosts AND idUser = :idUser";
            }

            $insertStmt = $db->prepare($insertQuery);

            $insertStmt->bindValue(':idPosts', $this->idPost);
            $insertStmt->bindValue(':idUser', UserService::current()->getIdUser());
            $insertStmt->bindValue(':action', $this->action);

            $insertStmt->execute();

            $db->commit();
            return true;

        } catch (\PDOException $e) {
            $db->rollBack();
            return false;
        }
    }



    public function delete(): bool
    {
        $db = Database::connection();

        $query = "DELETE FROM Posts 
                WHERE idPost = :idPost";

        $stmt = $db->prepare($query);

        $stmt->bindValue(':idPost', $this->idPost);

        return $stmt->execute();
    }

}