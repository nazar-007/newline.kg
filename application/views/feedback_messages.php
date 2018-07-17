<!DOCTYPE html>
<html>
<head>
    <title>Отправка письма разработчикам</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="shortcut icon" href="<?php echo base_url()?>uploads/images/design_images/default.jpg">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/messages.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/media.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/common.css">
</head>
<body>

<?php $this->load->view("header");?>
<div class="container">
    <div class="stuff row">
        <div class="pos_catalog small-hidden col-xs-3 col-sm-3">
            <?php $this->load->view('sidebar'); ?>
        </div>
        <div class="pos_feedback_messages col-xs-6 col-sm-9">
            <h1 class="centered">Напишите нам сообщение</h1>
            <p class="centered">Ваше мнение очень важно для нас!</p>
            <form action="javascript:void(0)" onsubmit="insertFeedbackMessage(this)">
                <input type="hidden" class="csrf" name="csrf_test_name" value="<?php echo $csrf_hash?>">
                <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']?>">
                <textarea id="feedback_message" name="message_text" rows="15" class="form-control" placeholder="Введите текст сообщения..."></textarea><br>
                <button type="submit" class="btn btn-success btn-lg center-block">Отправить сообщение</button>
            </form>
        </div>
    </div>
</div>
<?php $this->load->view("footer");?>


<script type="text/javascript" src="<?php base_url()?>js/common.js"></script>
<script>
    function insertFeedbackMessage(context) {
        var form = $(context)[0];
        var all_inputs = new FormData(form);
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>feedback_messages/insert_feedback_message",
            data: all_inputs,
            dataType: "JSON",
            contentType: false,
            processData: false
        }).done(function(message) {
            $('.csrf').val(message.csrf_hash);
            $('#feedback_message').val('');
            if (message.insert_message_id == 0) {
                alert(message.message_error);
            } else {
                alert(message.message_success);
            }
        })
    }
</script>
</body>
</html>