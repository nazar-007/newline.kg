<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<h1>User_id: <?php echo $_SESSION['user_id'];?></h1>

<h1>Время в Бишкеке: <?php echo date('H:i:s');?></h1>
<a href="/users/logout">Выйти</a>

<script>

    function putout() {
        $.ajax({
            method: "POST",
            url: "/users/logout",
            data: {id:'1', csrf_test_name: '<?php echo $csrf_hash?>'}
        }).done(function(messages) {
            alert(messages);
        })
    }
    window.onunload = putout;

</script>