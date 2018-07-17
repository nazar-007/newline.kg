<footer>
    <p>This is footer.</p>
</footer>
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
//    window.onload = onLine;

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
//    window.onunload = lastVisit;
</script>