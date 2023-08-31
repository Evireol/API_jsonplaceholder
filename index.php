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

$api = new UserPostAPI();

if (isset($_POST['delete_post'])) {
    $postIdToDelete = $_POST['delete_post'];
    $api->deletePost($postIdToDelete);
}

if (isset($_POST['add_post'])) {
    $api->addNewPost();
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Добавление, удаление и редактирование постов</title>
</head>
<body>
    <h1>Добавление, удаление и редактирование постов</h1>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <label for="user_id">Выберите пользователя:</label>
        <select name="user_id" id="user_id">
            <?php
            $users = $api->getUsers();
            foreach ($users as $user) {
                echo "<option value='{$user['id']}'>{$user['name']}</option>";
            }
            ?>
        </select>
        <br>
        <label for="post_id">Выберите пост:</label>
        <select name="post_id" id="post_id">
        </select>
        <br>
        <label for="title">Заголовок поста:</label>
        <input type="text" name="title" id="title"><br>
        <label for="body">Содержание поста:</label>
        <textarea name="body" id="body" rows="4"></textarea><br>
        <input type="submit" name="add_post" value="Добавить пост">
        <input type="submit" name="update_post" value="Сохранить">
    </form>

    <h2>Удаление поста</h2>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <label for="delete_user_id">Выберите пользователя для удаления поста:</label>
        <select name="delete_user_id" id="delete_user_id">
            <?php
            $users = $api->getUsers();
            foreach ($users as $user) {
                echo "<option value='{$user['id']}'>{$user['name']}</option>";
            }
            ?>
        </select>
        <br>
        <label for="delete_post">Выберите пост для удаления:</label>
        <select name="delete_post" id="delete_post">
        </select>
        <br>
        <input type="submit" value="Удалить пост">
    </form>

    <h2>Задания пользователя</h2>
    <ul id="user_tasks">
    </ul>

    <ul id="user_posts">
    </ul>

    <script>
        document.getElementById('user_id').addEventListener('change', function () {
            document.getElementById('post_id').innerHTML = '';
            document.getElementById('user_tasks').innerHTML = '';
            document.getElementById('user_posts').innerHTML = '';

            var userId = this.value;

            fetch('https://jsonplaceholder.typicode.com/posts?userId=' + userId)
                .then(function (response) {
                    return response.json();
                })
                .then(function (data) {
                    var postSelect = document.getElementById('post_id');

                    data.forEach(function (post) {
                        var option = document.createElement('option');
                        option.value = post.id;
                        option.textContent = post.title;
                        postSelect.appendChild(option);
                    });
                });

            fetch('https://jsonplaceholder.typicode.com/todos?userId=' + userId)
                .then(function (response) {
                    return response.json();
                })
                .then(function (data) {
                    var userTasksList = document.getElementById('user_tasks');

                    data.forEach(function (todo) {
                        var listItem = document.createElement('li');
                        listItem.textContent = todo.title;
                        userTasksList.appendChild(listItem);
                    });
                });
        });

        document.getElementById('post_id').addEventListener('change', function () {
            var postId = this.value;
            var userId = document.getElementById('user_id').value;

            fetch('https://jsonplaceholder.typicode.com/posts/' + postId)
                .then(function (response) {
                    return response.json();
                })
                .then(function (post) {
                    document.getElementById('title').value = post.title;
                    document.getElementById('body').value = post.body;
                });
        });

        document.getElementById('delete_user_id').addEventListener('change', function () {
            document.getElementById('delete_post').innerHTML = '';

            var userId = this.value;

            fetch('https://jsonplaceholder.typicode.com/posts?userId=' + userId)
                .then(function (response) {
                    return response.json();
                })
                .then(function (data) {
                    var postSelect = document.getElementById('delete_post');

                    data.forEach(function (post) {
                        var option = document.createElement('option');
                        option.value = post.id;
                        option.textContent = post.title;
                        postSelect.appendChild(option);
                    });
                });
        });
    </script>
</body>
</html>

