<?php

declare(strict_types=1);

namespace TheFileHub\Models;

use TheFileHub\Core\Database;

/**
 * Class User
 *
 * Represents a user entity with personal and account information.
 *
 * @property ?string $firstName       The user's first name.
 * @property ?string $lastName        The user's last name.
 * @property ?string $userName        The user's username.
 * @property ?string $email           The user's email address.
 * @property ?string $password        The user's hashed password.
 * @property ?string $profilePicture  The path or URL to the user's profile picture.
 * @property ?int    $roles           The user's role(s) identifier.
 * @property ?int    $level           The user's level.
 * @property ?int    $xp              The user's experience points.
 * @property array   $casts           Attribute casting definitions.
 */
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

    public ?int $roles = null {
        set => $this->roles = $value;
    }

    public ?int $level = null {
        set => $this->level = $value;
    }

    public ?int $xp = null {
        set => $this->xp = $value;
    }

    protected array $casts = [];

    /**
     * Get the user ID.
     *
     * @return int
     */
    public function getIdUser(): int
    {
        return $this->idUser;
    }

    /**
     * Get the first name of the user.
     *
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * Get the last name of the user.
     *
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * Get the username of the user.
     *
     * @return string
     */
    public function getUsername(): string
    {
        return $this->userName;
    }

    /**
     * Get the email of the user.
     *
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Get the hashed password of the user.
     *
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Get the profile picture URL/path.
     *
     * @return string
     */
    public function getProfilePicture(): string
    {
        return $this->profilePicture;
    }

    /**
     * Get the integer value of roles assigned to the user.
     *
     * @return int
     */
    public function getRoles(): int
    {
        return $this->roles;
    }

    /**
     * Set the roles for the user.
     *
     * @param int $roles
     * @return void
     */
    public function setRoles(int $roles): void
    {
        $this->roles = $roles;
    }

    /**
     * Convert a role integer to its string name.
     *
     * @param int $role
     * @return string
     */
    public function getRoleName(int $role): string
    {
        if ($role === 1) {
            return 'admin';
        }
        if ($role === 2) {
            return 'opperator';
        }
        if ($role === 3) {
            return 'creator';
        }
        return 'user';
    }

    /**
     * Get the user's current level.
     *
     * @return int
     */
    public function getLevel(): int
    {
        return $this->level;
    }

    /**
     * Get the user's current experience points (XP).
     *
     * @return int
     */
    public function getXp(): int
    {
        return $this->xp;
    }

    /**
     * Add XP to the user.
     *
     * @param int $xp
     * @return void
     */
    public function setXp_Add(int $xp): void
    {
        $this->xp += $xp;
    }

    /**
     * Retrieve all users from the database.
     *
     * @return User[] Array of User objects. Returns empty array if no users found.
     */
    public static function All(): array
    {
        $db = Database::connection();

        $query = "SELECT * FROM Users";

        $stmt = $db->prepare($query);

        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            foreach ($stmt->fetchAll() as $row) {
                $users[] = new self()->fill([
                    'idUser' => $row['idUser'],
                    'firstName' => $row['firstName'],
                    'lastName' => $row['lastName'],
                    'userName' => $row['userName'],
                    'email' => $row['email'],
                    'password' => $row['password'],
                    'roles' => $row['roles'],
                    'level' => $row['level'],
                    'xp' => $row['xp'],
                    'profilePicture' => $row['profilePicture']
                ]);
            }
        }

        return $users ?? [];
    }

    /**
     * Find a user by their ID.
     *
     * @param int $idUser
     * @return User|null Returns the User object if found, null otherwise.
     */
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

    /**
     * Find a user by their username.
     *
     * @param string $userName
     * @return User|null Returns the User object if found, null otherwise.
     */
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

    /**
     * Find a user by their email address.
     *
     * @param string $email
     * @return User|null Returns the User object if found, null otherwise.
     */
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

    /**
     * Insert a new user into the database.
     *
     * @return bool True on success, false on failure.
     */
    public function insert(): bool
    {
        $db = Database::connection();

        $query = "INSERT INTO Users (firstName, lastName, userName, email, password, roles, level, xp, profilePicture) VALUES (:firstName, :lastName, :userName, :email, :password, :roles, :level, :xp, :profilePicture)";

        $stmt = $db->prepare($query);

        $stmt->bindValue(':firstName', $this->firstName);
        $stmt->bindValue(':lastName', $this->lastName);
        $stmt->bindValue(':userName', $this->userName);
        $stmt->bindValue(':email', $this->email);
        $stmt->bindValue(':password', $this->password);
        $stmt->bindValue(':roles', $this->roles);
        $stmt->bindValue(':level', $this->level);
        $stmt->bindValue(':xp', $this->xp);
        $stmt->bindValue(':profilePicture', $this->profilePicture);

        $success = $stmt->execute();

        if ($success) {
            $this->idUser = (int) Database::connection()->lastInsertId();
        }

        return $success;
    }

    /**
     * Update the user's data in the database.
     *
     * @return bool True on success, false on failure.
     */
    public function update(): bool
    {
        $db = Database::connection();

        $query = "UPDATE Users SET firstName = :firstName, lastName = :lastName, userName = :userName, email = :email, password = :password, roles = :roles, level = :level, xp = :xp, profilePicture = :profilePicture WHERE idUser = :idUser";

        $stmt = $db->prepare($query);

        $stmt->bindValue(':firstName', $this->firstName);
        $stmt->bindValue(':lastName', $this->lastName);
        $stmt->bindValue(':userName', $this->userName);
        $stmt->bindValue(':email', $this->email);
        $stmt->bindValue(':password', $this->password);
        $stmt->bindValue(':roles', $this->roles);
        $stmt->bindValue(':level', $this->level);
        $stmt->bindValue(':xp', $this->xp);
        $stmt->bindValue(':profilePicture', $this->profilePicture);
        $stmt->bindValue(':idUser', $this->idUser);

        $success = $stmt->execute();

        return $success;
    }

    /**
     * Delete the user from the database.
     *
     * @return bool True on success, false on failure.
     */
    public function delete(): bool
    {
        $db = Database::connection();

        $query = "DELETE FROM Users WHERE idUser = :idUser";

        $stmt = $db->prepare($query);

        $stmt->bindValue(':idUser', $this->findByEmail($this->email)->idUser);

        return $stmt->execute();
    }
}
