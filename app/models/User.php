<?php
namespace App\models;

use App\core\Model;

/**
 * User table class model.
 * 
 * @author Ali Tavafi <ali.tavafii@gmail.com>
 */
class User extends Model
{
    /**
     * @var string Table name.
     */
    protected $table = "user";

    /**
     * User validation rules for user attributes.
     * 
     * @return array validation rules.
     */
    public function rules(): array
    {
        return [
            'string' => [
                'username',
                'password',
                'name',
                'family',
            ],
            'required' => [
                'username',
                'password',
                'name',
                'family',
                'email'
            ],
            'email' => ['email'],
            'unique' => ['username']
        ];
    }

    /**
     * Get all messages related to the user.
     * 
     * @param int $userId User id.
     * @param string $orderBy Order by type.
     * 
     * @return array|null user messages.
     */
    public function getMessagess(int $userId, string $orderBy = 'ASC'): ?array
    {
        return (new Message)->getAllByColumn('user_id', $userId, $orderBy);
    }

    /**
     * Get all user replies.
     * 
     * @param int $userId User id.
     * @param string $orderBy Order by type.
     * 
     * @return array|null user replies.
     */
    public function getReplies(int $userId, string $orderBy = 'ASC'): ?array
    {
        return (new Reply)->getAllByColumn('creator_id', $userId, $orderBy);
    }

    /**
     * Check loggedin and user_id indexes in session for login process
     * 
     * @param array $user User data.
     * 
     * @return int|bool user_id if user can login, or false if user can not log in.
     */
    public function login(array $user)
    {
        if (!isset($_SESSION['loggedin']) && !isset($_SESSION['user_id'])) {

            session_regenerate_id(true);

            $_SESSION['loggedin'] = true;
            $_SESSION['user_id'] = $user['id'];

            return $_SESSION['user_id'];
        }

        return false;
    }

    /**
     * check if user is logged in or not.
     * 
     * @return bool is logged in, or false if user is not logged in.
     */
    public static function isLoggedIn(): bool
    {
        if (isset($_SESSION['loggedin']) && isset($_SESSION['user_id'])) {
            if ($_SESSION['user_id'] != null) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get user id stored in session.
     * 
     * @return int|null user id.
     */
    public function getId(): ?int
    {
        if (isset($_SESSION['user_id'])) {
            return $_SESSION['user_id'];
        }

        return null;
    }

    /**
     * Check spechial characters in user data.
     * 
     * @param array $userData user data.
     * 
     * @return array Sanitized user data.
     */
    public static function sanitizeData(array $userData): array
    {
        foreach ($userData as $key => $data) {
            $userData[$key] = self::checkSpecialChars($data);
        }

        return $userData;
    }
}