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
