<?php
class UserPostAPI
{
    private $usersUrl = 'https://jsonplaceholder.typicode.com/users';
    private $postsUrl = 'https://jsonplaceholder.typicode.com/posts';

    public function addNewPost()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_post'])) {
            $userId = $_POST['user_id'];
            $title = $_POST['title'];
            $body = $_POST['body'];

            $postData = [
                'userId' => $userId,
                'title' => $title,
                'body' => $body,
            ];

            $this->createNewPost($postData);
        }
    }

    public function deletePost($postId)
    {
        $url = "{$this->postsUrl}/{$postId}";

        $options = [
            'http' => [
                'method' => 'DELETE',
            ],
        ];

        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);

        if ($result !== false) {
            echo "Пост успешно удален.";
        } else {
            echo "Ошибка при удалении поста.";
        }
    }

    public function getUsers()
    {
        $users = json_decode(file_get_contents($this->usersUrl), true);
        return $users;
    }

    public function getPostsByUser($userId)
    {
        $posts = json_decode(file_get_contents("{$this->postsUrl}?userId={$userId}"), true);
        return $posts;
    }

    private function createNewPost($postData)
    {
        $url = $this->postsUrl;

        $options = [
            'http' => [
                'method' => 'POST',
                'header' => 'Content-Type: application/json',
                'content' => json_encode($postData),
            ],
        ];

        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);

        if ($result !== false) {
            echo "Пост успешно добавлен.";
        } else {
            echo "Ошибка при добавлении поста.";
        }
    }
}
?>
