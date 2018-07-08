<div class="container">
    <div class="jumbotron">
        <h1>Bootstrap Tutorial</h1>
        <p>Bootstrap is the most popular HTML, CSS, and JS framework for developing responsive, mobile-first projects on the web.</p>
    </div>
    <p>This is some text.</p>
    <p>This is another text.</p>
</div>

<script>
    function onLine() {
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>users/online",
            data: {id:<?php echo $_SESSION['user_id']?>, csrf_test_name: $(".csrf").val()},
            dataType: "JSON"
        }).done(function(message) {
            $('.csrf').val(message.csrf_hash);
        })
    }
    window.onload = onLine;

    function lastVisit() {
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>users/last_visit",
            data: {id:<?php echo $_SESSION['user_id']?>, csrf_test_name: $(".csrf").val()},
            dataType: "JSON"
        }).done(function(message) {
            $('.csrf').val(message.csrf_hash);
        })
    }
    window.onunload = lastVisit;
</script>