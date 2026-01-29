<?php

declare(strict_types=1);

namespace TheFileHub\Models;

use TheFileHub\Core\Database;

class User extends AbstractModel
{
    /**
     * Primary key
     *
     * @var string
     */
    protected static ?string $primaryKey = 'idUser';

    /**
     * User ID
     *
     * @var int|null
     */
    public ?int $idUser = null;

    public ?string $firstName = null {
        set => $this->firstName = $value;
    }

    public ?string $lastName = null {
        set => $this->lastName = $value;
    }

    public ?string $userName = null {
        set => $this->userName = $value;
    }

    public ?string $email = null {
        set => $this->email = $value;
    }

    public ?string $password = null {
        set => $this->password = $value;
    }

    public ?string $profilePicture = null {
        set => $this->profilePicture = $value;
    }

    protected array $casts = [];

    public function getIdUser(): int
    {
        return $this->idUser;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getUsername(): string
    {
        return $this->userName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getProfilePicture(): string
    {
        return $this->profilePicture;
    }

    public static function All(): array
    {
        $db = Database::connection();

        $query = "SELECT * FROM Users";

        $stmt = $db->prepare($query);

        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            foreach ($stmt->fetchAll() as $row) {
                $users[] = new self()->fill([
                    'firstName' => $row['firstName'],
                    'lastName' => $row['lastName'],
                    'userName' => $row['userName'],
                    'email' => $row['email'],
                    'password' => $row['password'],
                    'profilePicture' => $row['profilePicture']
                ]);
            }
        }

        return $users ?? [];
    }

    public static function findById(int $idUser): User|null
    {
        $db = Database::connection();

        $query = "SELECT * FROM Users WHERE idUser = :idUser";

        $stmt = $db->prepare($query);

        $stmt->bindValue(':idUser', $idUser);

        $stmt->execute();

        $row = $stmt->fetch();

        return $row ? new self()->fill($row) : null;
    }

    public static function findByUsername(string $userName): User|null
    {
        $db = Database::connection();

        $query = "SELECT * FROM Users WHERE userName = :userName";

        $stmt = $db->prepare($query);

        $stmt->bindValue(':userName', $userName);

        $stmt->execute();

        $row = $stmt->fetch();

        return $row ? new self()->fill($row) : null;
    }

    public static function findByEmail(string $email): User|null
    {
        $db = Database::connection();

        $query = "SELECT * FROM Users WHERE email = :email";

        $stmt = $db->prepare($query);

        $stmt->bindValue(':email', $email);

        $stmt->execute();

        $row = $stmt->fetch();

        return $row ? new self()->fill($row) : null;
    }

    public function insert(): bool
    {
        $db = Database::connection();

        $query = "INSERT INTO Users (firstName, lastName, userName, email, password, profilePicture) VALUES (:firstName, :lastName, :userName, :email, :password, :profilePicture)";

        $stmt = $db->prepare($query);

        $stmt->bindValue(':firstName', $this->firstName);
        $stmt->bindValue(':lastName', $this->lastName);
        $stmt->bindValue(':userName', $this->userName);
        $stmt->bindValue(':email', $this->email);
        $stmt->bindValue(':password', $this->password);
        $stmt->bindValue(':profilePicture', $this->profilePicture);

        $success = $stmt->execute();

        if ($success) {
            $this->idUser = (int) Database::connection()->lastInsertId();
        }

        return $success;
    }

    public function update(): bool
    {
        $db = Database::connection();

        $query = "UPDATE Users SET firstName = :firstName, lastName = :lastName, userName = :userName, email = :email, password = :password, profilePicture = :profilePicture WHERE idUser = :idUser";

        $stmt = $db->prepare($query);

        $stmt->bindValue(':firstName', $this->firstName);
        $stmt->bindValue(':lastName', $this->lastName);
        $stmt->bindValue(':userName', $this->userName);
        $stmt->bindValue(':email', $this->email);
        $stmt->bindValue(':password', $this->password);
        $stmt->bindValue(':profilePicture', $this->profilePicture);
        $stmt->bindValue(':idUser', $this->idUser);

        $success = $stmt->execute();

        return $success;
    }

    public function delete(): bool
    {
        $db = Database::connection();

        $query = "DELETE FROM Users WHERE idUser = :idUser";

        $stmt = $db->prepare($query);

        $stmt->bindValue(':idUser', $this->findByEmail($this->email)->idUser);

        return $stmt->execute();
    }
}
