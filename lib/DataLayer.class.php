<?php
require_once("lib/db_parms.php");

class DataLayer
{

    private $connexion;
    public function __construct()
    {

        $this->connexion = new PDO(
            DB_DSN,
            DB_USER,
            DB_PASSWORD,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]
        );
        $this->connexion->exec("SET search_path TO rezozio"); 
    }

    public function getUser($userId)
    {
        $request = 'SELECT users.login AS "userId", users.pseudo FROM users WHERE users.login = :userId';
        $stmt = $this->connexion->prepare($request);
        $stmt->bindValue(':userId', $userId);
        $stmt->execute();
        $user = $stmt->fetch();
        if ($user) return $user;
        return null;
    }

    public function getProfile($userId, $current)
    {
        $request = 'SELECT users.login AS "userId", users.pseudo, users.description, s1.target IS NOT NULL AS "followed", s2.target IS NOT NULL AS "isFollower"
                    FROM users
                    LEFT JOIN subscriptions AS s1 ON users.login = s1.target AND s1.follower = :current
                    LEFT JOIN subscriptions AS s2 ON users.login = s2.follower AND s2.target = :current
                    WHERE users.login = :userId';
        $stmt = $this->connexion->prepare($request);
        $stmt->bindValue(':userId', $userId);
        $stmt->bindValue(':current', $current);
        $stmt->execute();
        $profile = $stmt->fetch();
        if (!is_null($profile)) return $profile;
        return null;
    }

    public function getMessage($messageId)
    {
        $request = 'SELECT messages.id AS "messageId", messages.author, users.pseudo, messages.content, messages.datetime
                    FROM messages
                    LEFT JOIN users ON messages.author = users.login
                    WHERE messages.id = :messageId';
        $stmt = $this->connexion->prepare($request);
        $stmt->bindValue(':messageId', $messageId);
        $stmt->execute();
        if ($stmt->rowCount() == 0) return null;
        $message = $stmt->fetch();
        return $message;
    }

    public function getAvatar($userId, $size)
    {
        if ($size === "small") $request = 'SELECT avatar_type, avatar_small AS "avatar" FROM users WHERE users.login = :userId';
        else if ($size === "large") $request = 'SELECT avatar_type, avatar_large AS "avatar" FROM users WHERE users.login = :userId';
        $stmt = $this->connexion->prepare($request);
        $stmt->bindValue(':userId', $userId);
        $stmt->bindColumn('mimetype', $mimeType);
        $stmt->bindColumn('avatar', $flow, PDO::PARAM_LOB);
        $stmt->execute();
        $avatar = $stmt->fetch();
        if ($avatar)
            return ['mimetype'=>$mimeType,'data'=>$flow];
        else
            return false;
    }

    public function userExists($userId)
    {
        $request = "SELECT users.login FROM users WHERE users.login = :userId";
        $stmt = $this->connexion->prepare($request);
        $stmt->bindValue(':userId', $userId);
        $stmt->execute();
        $res = $stmt->rowCount();
        if ($res > 0) return true;
        return false;
    }

    public function createUser($userId, $password, $pseudo)
    {
        if (strlen($userId) <= 25 && strlen($pseudo) <= 25)
        {
            $request = "INSERT INTO users(login, password, pseudo) VALUES (:userId, :password, :pseudo) RETURNING login, pseudo";
            $stmt = $this->connexion->prepare($request);
            $stmt->bindValue(':userId', $userId);
            $encrypted_password = password_hash($password, CRYPT_BLOWFISH);
            $stmt->bindValue(':password', $encrypted_password);
            $stmt->bindValue(':pseudo', $pseudo);
            $stmt->execute();
            $res = $stmt->fetch();
            return array(
                'userId' => $res['login'],
                'pseudo' => $res['pseudo']
            );
        }
        return null;
    }

    public function findUsers($searchedString)
    {
        $request = 'SELECT users.login AS "userId", users.pseudo FROM users 
                    WHERE users.login LIKE :searchedString OR users.pseudo LIKE :searchedString';
        $stmt = $this->connexion->prepare($request);
        $stmt->bindValue('searchedString', '%' . $searchedString . '%', PDO::PARAM_STR);
        $stmt->execute();
        $res = $stmt->fetchAll();
        return $res;
    }

    public function login($userId, $password)
    {
        $request = 'SELECT users.login AS "userId", users.password FROM users WHERE users.login=:userId';
        $stmt = $this->connexion->prepare($request);
        $stmt->bindValue(':userId', $userId);
        $stmt->execute();
        $res = $stmt->fetch();
        if (!$res) return null;
        if (crypt($password, $res['password']) != $res['password']) return null;
        return $res;
    }

    public function findMessages($author, $before, $count)
    {
        if ($author == "")
        {
            $request = "SELECT messages.id, messages.author, users.pseudo, messages.content, messages.datetime FROM messages
                        INNER JOIN users ON messages.author = users.login
                        WHERE messages.id < :before
                        ORDER BY messages.id DESC LIMIT :count";
            $stmt = $this->connexion->prepare($request);
        }
        else
        {
            $request = "SELECT messages.id, messages.author, users.pseudo, messages.content, messages.datetime FROM messages
                        INNER JOIN users ON messages.author = users.login
                        WHERE messages.author = :author AND messages.id < :before
                        ORDER BY messages.id DESC LIMIT :count";
            $stmt = $this->connexion->prepare($request);
            $stmt->bindValue(':author', $author);
        }
        $stmt->bindValue(':before', $before);
        $stmt->bindValue(':count', $count);
        $stmt->execute();
        $messages = $stmt->fetchAll();
        return $messages;
    }

    public function findFollowedMessages($userId, $before, $count)
    {
        $request = "SELECT messages.id, messages.author, users.pseudo, messages.content, messages.datetime FROM messages
                    INNER JOIN users ON messages.author = users.login
                    RIGHT JOIN subscriptions ON subscriptions.follower = :userId AND subscriptions.target = messages.author
                    WHERE subscriptions.follower = :userId AND subscriptions.target = messages.author AND messages.id < :before
                    ORDER BY messages.id DESC LIMIT :count";
        $stmt = $this->connexion->prepare($request);
        $stmt->bindValue(':userId', $userId);
        $stmt->bindValue(':before', $before);
        $stmt->bindValue(':count', $count);
        $stmt->execute();
        $messages = $stmt->fetchAll();
        return $messages;
    }

    public function postMessage($userId, $message)
    {
        $request = "INSERT INTO messages(author, content) VALUES (:userId, :message) RETURNING messages.id";
        $stmt = $this->connexion->prepare($request);
        $stmt->bindValue(':userId', $userId);
        $stmt->bindValue(':message', $message);
        $stmt->execute();
        $message = $stmt->fetch();
        return $message['id'];
    }

    public function setProfile($userId, $password, $pseudo, $description)
    {
        if ($password != "")
        {
            $request = "UPDATE users 
                        SET password = :password
                        WHERE users.login = :userId"; 
            $stmt = $this->connexion->prepare($request);
            $stmt->bindValue(':userId', $userId);
            $encrypted_password = password_hash($password, CRYPT_BLOWFISH);
            $stmt->bindValue(':password', $encrypted_password);
            $stmt->execute();
        }
        if ($pseudo != "")
        {
            $request = "UPDATE users 
                        SET pseudo = :pseudo
                        WHERE users.login = :userId";
            $stmt = $this->connexion->prepare($request);
            $stmt->bindValue(':pseudo', $pseudo);
            $stmt->bindValue(':userId', $userId);
            $stmt->execute();
        }
        if ($description != "")
        {
            $request = "UPDATE users 
                        SET description = :description
                        WHERE users.login = :userId";     
            $stmt = $this->connexion->prepare($request);
            $stmt->bindValue(':description', $description);
            $stmt->bindValue(':userId', $userId);
            $stmt->execute();
        }

        $request = 'SELECT users.login AS "userId", users.pseudo FROM users WHERE users.login = :userId';
        $stmt = $this->connexion->prepare($request);
        $stmt->bindValue(':userId', $userId);
        $stmt->execute();
        $res = $stmt->fetch();
        return $res;
    }

    public function uploadAvatar($userId, $flow, $type, $size)
    {
        if ($size === "small")
            $request = "UPDATE users SET avatar_small = :avatar, avatar_type = :mimetype WHERE users.login = :userId";
        if ($size === "large")
            $request = "UPDATE users SET avatar_large = :avatar, avatar_type = :mimetype WHERE users.login = :userId";
        try {
            $stmt = $this->connexion->prepare($request);
            $stmt->bindValue(':avatar', $flow, PDO::PARAM_LOB);
            $stmt->bindValue(':mimetype', $type);
            $stmt->bindValue(':userId', $userId);
            $stmt->execute();
            return true;
        }
        catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function isFollowing($userId, $target)
    {
        $request = "SELECT subscriptions.follower FROM subscriptions 
                    WHERE subscriptions.follower = :userId AND subscriptions.target = :target";
        $stmt = $this->connexion->prepare($request);
        $stmt->bindValue(':userId', $userId);
        $stmt->bindValue(':target', $target);
        $stmt->execute();
        $res = $stmt->rowCount();
        if ($res > 0) return true;
        return false;
    }

    public function follow($userId, $target)
    {
        $request = "INSERT INTO subscriptions(follower, target) VALUES (:userId, :target)";
        $stmt = $this->connexion->prepare($request);
        $stmt->bindValue(':userId', $userId);
        $stmt->bindValue(':target', $target);
        $stmt->execute();
        return true;
    }

    public function unfollow($userId, $target)
    {
        $request = "DELETE FROM subscriptions WHERE subscriptions.follower = :userId AND subscriptions.target = :target";
        $stmt = $this->connexion->prepare($request);
        $stmt->bindValue(':userId', $userId);
        $stmt->bindValue(':target', $target);
        $stmt->execute();
        return true;
    }

    public function getFollowers($userId)
    {
        $request = 'SELECT subscriptions.follower AS "userId", users.pseudo FROM subscriptions 
                    INNER JOIN users ON users.login = subscriptions.follower
                    WHERE subscriptions.target = :userId';
        $stmt = $this->connexion->prepare($request);
        $stmt->bindValue(':userId', $userId);
        $stmt->execute();
        $res = $stmt->fetchAll();
        return $res;
    }

    public function getSubscriptions($userId)
    {
        $request = 'SELECT subscriptions.target AS "userId", users.pseudo FROM subscriptions
        INNER JOIN users ON users.login = subscriptions.target 
        WHERE subscriptions.follower = :userId';
        $stmt = $this->connexion->prepare($request);
        $stmt->bindValue(':userId', $userId);
        $stmt->execute();
        $res = $stmt->fetchAll();
        return $res;
    }

}
