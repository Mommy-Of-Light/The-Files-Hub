<?php

declare(strict_types=1);

namespace TheFileHub\Models;

use Slim\Psr7\Response;
use TheFileHub\Core\Database;
use TheFileHub\Services\UserService;

/**
 * Class Post
 *
 * Represents a post entity in the application.
 * Handles CRUD operations, likes/dislikes management, and user associations.
 *
 * @property int|null    $idPost      The unique identifier for the post.
 * @property int|null    $idCreator   The unique identifier for the creator (user) of the post.
 * @property string|null $name        The name/title of the post.
 * @property string|null $fileLink    The link to the file associated with the post.
 * @property string|null $description The description of the post.
 * @property int|null    $likes       The number of likes for the post.
 * @property int|null    $dislikes    The number of dislikes for the post.
 * @property string      $fileExt     The file extension of the post's file.
 * @property int         $action      The current user's action on the post (-1: none, 0: dislike, 1: like).
 */
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

    protected array $casts = [];

    public string $fileExt = '';

    public int $action = -1;

    /**
     * Get the post ID.
     *
     * @return int
     */
    public function getPost(): int
    {
        return $this->idPost;
    }

    /**
     * Get the post name/title.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set the post name/title.
     *
     * @param string $name
     * @return void
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Get the creator (User) of the post.
     *
     * @return User
     */
    public function getCreator(): User
    {
        return User::findById($this->idCreator);
    }

    /**
     * Set the creator ID of the post.
     *
     * @param int $idCreator
     * @return void
     */
    public function setCreator(int $idCreator): void
    {
        $this->idCreator = $idCreator;
    }

    /**
     * Get the file link of the post.
     *
     * @return string
     */
    public function getFileLink(): string
    {
        return $this->fileLink;
    }

    /**
     * Set the file link of the post.
     *
     * @param string $fileLink
     * @return void
     */
    public function setFileLink(string $fileLink): void
    {
        $this->fileLink = $fileLink;
    }

    /**
     * Get the description of the post.
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Set the description of the post.
     *
     * @param string $description
     * @return void
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * Get the number of likes.
     *
     * @return int
     */
    public function getLikes(): int
    {
        return $this->likes;
    }

    /**
     * Set the number of likes.
     *
     * @param int $likes
     * @return void
     */
    public function setLikes(int $likes): void
    {
        $this->likes = $likes;
    }

    /**
     * Get the number of dislikes.
     *
     * @return int
     */
    public function getDislikes(): int
    {
        return $this->dislikes;
    }

    /**
     * Set the number of dislikes.
     *
     * @param int $dislikes
     * @return void
     */
    public function setDislikes(int $dislikes): void
    {
        $this->dislikes = $dislikes;
    }

    /**
     * Get the file extension.
     *
     * @return string
     */
    public function getFileExt(): string
    {
        return $this->fileExt;
    }

    /**
     * Set the file extension.
     *
     * @param string $fileExt
     * @return void
     */
    public function setFileExt(string $fileExt): void
    {
        $this->fileExt = $fileExt;
    }

    /**
     * Get the current user's action on this post (-1: none, 0: dislike, 1: like)
     *
     * @return int
     */
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

    /**
     * Retrieve all posts from the database.
     *
     * @return Post[] Array of Post objects. Returns an empty array if no posts found.
     */
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

    /**
     * Find a single post by its ID.
     *
     * @param int $idPost The ID of the post to find.
     * @return Post|null Returns the Post object if found, or null if not found.
     */
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

    /**
     * Find a post by the creator's ID.
     *
     * @param string $idCreator The user ID of the creator.
     * @return Post|null Returns the Post object if found, or null if not found.
     */
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

    /**
     * Modify the current user's like or dislike on the post.
     *
     * @param Response $response The Slim response object for redirection.
     * @param string $type The action type: 'like' or 'dislike'.
     * @return Response The response object, possibly with redirection headers if action was invalid.
     */
    public function modifyLikes($response, string $type): Response
    {
        $db = Database::connection();

        $userId = UserService::current()->getIdUser();

        $check = $db->prepare("SELECT * FROM PostsUsers WHERE idPosts = :idPost AND idUser = :idUser");
        $check->bindValue(':idPost', $this->idPost);
        $check->bindValue(':idUser', $userId);
        $check->execute();
        $data = $check->fetchAll();

        $creator = $this->getCreator();

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
            } elseif ($row['action'] === 0 && $type === 'like') {
                $update = $db->prepare("UPDATE PostsUsers SET action = 1 WHERE idPosts = :idPost AND idUser = :idUser");
                $update->bindValue(':idPost', $this->idPost);
                $update->bindValue(':idUser', $userId);
                $update->execute();
                $this->setDislikes($this->getDislikes() - 1);
            }
        }

        $creator->update();

        return $response;
    }

    /**
     * Insert a new post into the database.
     *
     * @return bool True on success, false on failure.
     */
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

    /**
     * Update the post in the database and the user's action (like/dislike).
     *
     * @return bool True on success, false on failure.
     */
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

    /**
     * Delete the post from the database.
     *
     * @return bool True on success, false on failure.
     */
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