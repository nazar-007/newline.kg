<!DOCTYPE html>
<html>
<head>
    <title>Мой документ</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="shortcut icon" href="<?php echo base_url()?>uploads/icons/logo.png">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/documents.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/media.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/common.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/animate.css">
</head>
<body>

<?php $this->load->view("header");?>
<div class="container">
    <div class="stuff row">
        <div class="pos_catalog small-hidden col-xs-3 col-sm-3">
            <?php $this->load->view('sidebar'); ?>
        </div>
        <div class="pos_documents col-xs-6 col-sm-9">
            <?php
            if ($documents_error != '') {
                echo "<h1 class='red centered'>" . $documents_error . "
                <br><a href='" . base_url() . "folders'>Назад</a></h1>";
                die();
            }
            ?>
            <br>
            <div class="documents">
                <div class='document row'>
                    <div class='col-xs-6'>
                        <a href='<?php echo base_url()?>folders'>
                            <img src='<?php echo base_url()?>uploads/icons/folder.png'>
                            <span class='document_name'>
                                Все мои папки
                            </span>
                        </a>
                    </div>
                    <div class='col-xs-6'>
                        <a href='<?php echo base_url() . "documents/$folder_id"?>'>
                            <img src='<?php echo base_url()?>uploads/icons/document.png'>
                            <span class='document_name'>
                                    ../
                            </span>
                        </a>
                    </div>
                </div>
                <form class="document_form" action="javascript:void(0)" onsubmit="updateDocument(this)">
                    <button class="btn btn-primary center-block" data-toggle="modal" data-target="#updateDocument">Сохранить изменения</button>
                    <input type="hidden" class="csrf" name="csrf_test_name" value="<?php echo $csrf_hash?>">
                    <input type="hidden" name="id" value="<?php echo $one_document[0]->id?>">
                    <label>Название документа</label>
                    <input class="form-control document_name" name="document_name" value="<?php echo $one_document[0]->document_name?>">
                    <label>Содержимое документа</label>
                    <textarea name="document_description" rows="15" class="form-control document_description" placeholder="Введите текст документа..."><?php echo $one_document[0]->document_description;?></textarea>
                </form>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view("footer");?>


<script type="text/javascript" src="<?php base_url()?>js/common.js"></script>
<script>

    function updateDocument(context) {
        var form = $(context)[0];
        var all_inputs = new FormData(form);
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>" + "documents/update_document",
            data: all_inputs,
            dataType: "JSON",
            contentType: false,
            processData: false
        }).done(function(message) {
            $(".csrf").val(message.csrf_hash);
            if (message.document_error) {
                alert(message.document_error);
            }
            if (message.document_success) {
                alert(message.document_success);
            }

        })
    }

</script>

</body>
</html>