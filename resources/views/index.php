<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>

</head>
<style>
body {

}
    form {
    display: flex;
    flex-direction: column;
        margin: 15vh auto;
        max-width: 500px;
    }
    label {
    margin-bottom: 5px;
    }
    input {
    margin-bottom: 10px;
        padding: 3px;
    }
    textarea {
    padding: 3px;
        font-size: 16px;
        margin-bottom: 10px;
    }
</style>
<body>
    <form action="/" method="post">
        <label for="name">Имя</label>
        <input type="text" id="name" name="name" required>
        <label for="telephone">Телефон</label>
        <input type="text" id="telephone" name="telephone" required>
        <label for="comment">Комментарий</label>
        <textarea id="comment" name="comment" required></textarea>
        <input type="submit" value="Отправить">
    </form>
</body>
</html>

