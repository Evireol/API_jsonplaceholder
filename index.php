<?php

require_once('api.php'); 

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

    <script src="script.js" defer></script> 
</body>
</html>

