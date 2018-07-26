<!DOCTYPE html>
<html>
<head>
    <title>Редактирование данных</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="shortcut icon" href="<?php echo base_url()?>uploads/icons/logo.png">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/update.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/media.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/common.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/animate.css">
    <style>
        .forms {
            display: none;
        }
    </style>
</head>
<body>

<?php $this->load->view("header");?>
<div class="container">
    <div class="stuff row">
        <div class="pos_catalog small-hidden col-xs-3 col-sm-3">
            <?php $this->load->view('sidebar'); ?>
        </div>
        <div class="pos_update col-xs-6 col-sm-9">
            <div class="row friend-row">
                <div onclick="chooseUpdateForm(this)" data-id="0" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 update-column active-column">
                    Данные                 <?php echo $_SESSION['user_email']?>
                </div>
                <div onclick="chooseUpdateForm(this)" data-id="1" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 update-column">
                    Пароль
                </div>
                <div onclick="chooseUpdateForm(this)" data-id="2" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 update-column">
                    Вопросы
                </div>
                <div onclick="chooseUpdateForm(this)" data-id="3" class="col-xs-3 col-sm-3 col-md-3 col-lg-3 update-column">
                    Фотка
                </div>
            </div>
            <div id="update_field">
                <form class="forms" style="display: block" action="javascript:void(0)" onsubmit="updateUser(this)">
                    <h3 class="centered">Редактирование данных</h3>
                    <input class="csrf" type="hidden" name="csrf_test_name" value="<?php echo $csrf_hash?>">
                    <input type="hidden" name="id" value="<?php echo $_SESSION['user_id']?>">
                    <?php foreach ($users as $user):?>
                    <div class="form-group">
                        <label for="email">Ваш email:</label>
                        <input type="text" class="form-control common_input" id="email" name="email" value="<?php echo $user->email?>">
                        <div id="email_error" class="error"></div>
                    </div>
                    <div class="form-group">
                        <label for="nickname">Ваше имя:</label>
                        <input type="text" class="form-control common_input" id="nickname" name="nickname" value="<?php echo $user->nickname?>">
                        <div id="nickname_error" class="error"></div>
                    </div>
                    <div class="form-group">
                        <label for="surname">Ваша фамилия:</label>
                        <input type="text" class="form-control common_input" id="surname" name="surname" value="<?php echo $user->surname?>">
                        <div id="surname_error" class="error"></div>
                    </div>
                    <div class="form-group">
                        <label>Ваша дата рождения:</label><br>
                        <select id="day" name="day" class="birth-date">
                            <option value="">День</option>
                            <script>
                                for (var i = 1; i <= 31; i++) {
                                    document.write("<option>" + i + "</option>");
                                }
                            </script>
                        </select>
                        <select id="month" name="month" class="birth-date">
                            <option value="">Месяц</option>
                            <script>
                                var months = ["Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"];
                                var months_of = ["января", "февраля", "марта", "апреля", "мая", "июня", "июля", "августа", "сентября", "октября", "ноября", "декабря"];
                                for (var i = 0; i < months.length; i++) {
                                    document.write("<option value='" + months_of[i] + "'>" + months[i] + "</option>");
                                }
                            </script>
                        </select>
                        <select id="birth_year" name="birth_year" class="birth-date">
                            <option value="">Year</option>
                            <script>
                                for (var i = 2018; i >= 1918; i--) {
                                    document.write("<option>" + i + "</option>");
                                }
                            </script>
                        </select><br>
                        <div id="birth_date_error" class="error"></div>
                    </div>
                    <div class="form-group">
                        <label>Ваш пол:</label><br>
                        <input type="radio" name="gender" value="Мужчина" <?php if ($user->gender == 'Мужчина') echo 'checked'?>>Мужчина
                        <input type="radio" name="gender" value="Женщина" <?php if ($user->gender == 'Женщинаа') echo 'checked'?>>Женщина
                        <input type="radio" name="gender" value="Скрыто" <?php if ($user->gender == 'Скрыто') echo 'checked'?>>Скрыто<br>
                        <div id="gender_error" class="error"></div>
                    </div>
                    <div class="form-group">
                        <label>Ваша страна:</label><br>
                        <select id="home_land" name="home_land">
                            <option value="">Страна</option>
                            <?php
                            foreach ($countries as $country) {
                                echo "<option>" . $country->country_name. "</option>";
                            }
                            ?>
                        </select><br>
                    </div>
                    <div class="form-group">
                        <label for="school_1">Ваша школа:</label>
                        <input type="text" class="form-control common_input" id="school_1" placeholder="Введите название школы" name="education_schools[]">
                        <img id="school_image" onclick="addNewSchool(event)" class="plus" src="<?php echo base_url()?>uploads/icons/plus.png">
                    </div>
                    <div class="form-group">
                        <label for="university_1">Ваш университет:</label>
                        <input type="text" class="form-control common_input" id="university_1" placeholder="Введите название университета" name="education_universities[]">
                        <img id="university_image" onclick="addNewUniversity(event)" class="plus" src="<?php echo base_url()?>uploads/icons/plus.png">
                    </div>
                    <div class="form-group">
                        <label>Ваше семейное положение:</label><br>
                        <select id="family_position" name="family_position">
                            <option value="">Семейное положение</option>
                            <script>
                                var family_positions = ["Женат", "Замужем", "Есть подруга", "Есть друг", "Свободен", "Свободна", "Всё сложно"];
                                for (var i = 0; i <= family_positions.length; i++) {
                                    document.write("<option>" + family_positions[i] + "</option>");
                                }
                            </script>
                        </select><br>
                    </div>
                <button type="submit" class="btn btn-success center-block">Изменить</button>
                        <h4><b>Примечание:</b><br> Если Вы не измените дату рождения, страну, образование, семейное положение, эти поля останутся в том числе прежними.</h4>

                    <?php endforeach;?>
                </form>
                <form class="forms" action="javascript:void(0)" onsubmit="updatePassword(this)">
                    <h3 class="centered">Редактирование пароля</h3>
                    <input type="hidden" class="csrf" name="csrf_test_name" value="<?php echo $csrf_hash;?>">
                    <input type="hidden" name="id" value="<?php echo $_SESSION['user_id']?>">
                    <label>Ваш текущий пароль: </label>
                    <input type="password" class="form-control" name="current_password">
                    <div id="current_password_error" class="red"></div>
                    <label>Ваш новый пароль: </label>
                    <input type="password" class="form-control" name="new_password">
                    <div id="new_password_error" class="red"></div>
                    <label>Повторите новый пароль: </label>
                    <input type="password" class="form-control" name="check_new_password"><br>
                    <button class="btn btn-success center-block">Поменять пароль</button>
                </form>
                <form class="forms" action="javascript:void(0)" onsubmit="updateSecretQuestionsAndSecretAnswers(this)">
                    <h3 class="centered">Редактирование секретных вопросов</h3>
                    <input type="hidden" class="csrf" name="csrf_test_name" value="<?php echo $csrf_hash;?>">
                    <input type="hidden" name="id" value="<?php echo $_SESSION['user_id']?>">
                    <div class="form-group">
                        <label>Ваш новый секретный вопрос №1</label><br>
                        <select name="secret_question_1" class="secret_question">
                            <option value="">Секретный вопрос</option>
                            <script>
                                var secret_questions_1 = ["Любимый цвет", "Любимая еда", "Любимый фильм", "Любимая книга", "Любимая песня", "Любимая одежда", "Любимая машина", "Любимая страна", "Любимый напиток", "Любимое имя", "Любимая дата"];
                                for (var i = 0; i < secret_questions_1.length; i++) {
                                    document.write("<option>" + secret_questions_1[i] + "</option>");
                                }
                            </script>
                        </select><br>
                        <div id="secret_question_1_error" class="error"></div>
                    </div>
                    <div class="form-group">
                        <label for="secret_answer_1">Ваш новый ответ на 1 новый вопрос:</label>
                        <input type="text" class="form-control common_input" id="secret_answer_1" placeholder="Введите ответ на 1 вопрос" name="secret_answer_1">
                        <div id="secret_answer_1_error" class="error"></div>
                    </div>
                    <div class="form-group">
                        <label>Ваш новый секретный вопрос №2</label><br>
                        <select name="secret_question_2" class="secret_question">
                            <option value="">Секретный вопрос</option>
                            <script>
                                var secret_questions_2 = ["Нелюбимый цвет", "Нелюбимая еда", "Нелюбимый фильм", "Нелюбимая книга", "Нелюбимая песня", "Нелюбимая одежда", "Нелюбимая машина", "Нелюбимая страна", "Нелюбимый напиток", "Нелюбимое имя", "Нелюбимая дата"];
                                for (var i = 0; i < secret_questions_2.length; i++) {
                                    document.write("<option>" + secret_questions_2[i] + "</option>");
                                }
                            </script>
                        </select><br>
                        <div id="secret_question_2_error" class="error"></div>
                    </div>
                    <div class="form-group">
                        <label for="secret_answer_2">Ваш новый ответ на 2 новый вопрос:</label>
                        <input type="text" class="form-control common_input" id="secret_answer_2" placeholder="Введите ответ на 2 вопрос" name="secret_answer_2">
                        <div id="secret_answer_2_error" class="error"></div>
                    </div>
                    <button class="btn btn-success center-block">Поменять секретные вопросы</button>
                </form>
                <form class="forms">
                    <h3 class="centered">Редактирование основной фотки</h3>
                    <input type="text" placeholder="3">
                </form>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view("footer");?>

<script type="text/javascript" src="<?php base_url()?>js/common.js"></script>
<script>

    var school = 1;
    function addNewSchool(e) {
        school = school + 1;
        var i = parseInt(school);
        $(e.target).before("<div class='form-group'>" +
            "<label for='school_" + i + "'>Ваша школа №" + i + ": <img onclick='removeInput(this.parentElement.parentElement)' class='cross' src='<?php echo base_url()?>uploads/icons/cancel.png'></label>" +
            "<input type='text' class='form-control common_input' id='school_" + i + "' placeholder='Введите название школы' name='education_schools[]'>" +
            "</div>");
        if (i >= 3) {
            $("#school_image").remove();
        }
    }

    var university = 1;
    function addNewUniversity(e) {
        university = university + 1;
        var i = parseInt(university);
        $(e.target).before("<div class='form-group'>" +
            "<label for='university_" + i + "'>Ваш университет №" + i + ": <img onclick='removeInput(this.parentElement.parentElement)' class='cross' src='<?php echo base_url()?>uploads/icons/cancel.png'></label>" +
            "<input type='text' class='form-control common_input' id='university_" + i + "' placeholder='Введите название университета' name='education_universities[]'>" +
            "</div>");
        if (i >= 3) {
            $("#university_image").remove();
        }
    }

    function removeInput(context) {
        $(context).remove();
    }

    function chooseUpdateForm(context) {
        var id = context.getAttribute('data-id');
        var forms = document.getElementsByClassName('forms');
        $('.update-column').removeClass('active-column');
        $('.update-column').eq(id).addClass('active-column');
        for (var i = 0; i < forms.length; i++) {
            if (i == id) {
                forms[i].style.display = 'block';
            } else {
                forms[i].style.display = 'none';
            }
        }
    }

    function updateUser(context) {
        var form = $(context)[0];
        var all_inputs = new FormData(form);
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>users/update_user",
            data: all_inputs,
            dataType: "JSON",
            contentType: false,
            processData: false
        }).done(function(message) {
            $('.csrf').val(message.csrf_hash);
            if (message.email_num_rows === 1) {
                $("#email_error").html(message.email_exist);
            } else if (message.email_empty) {
                $("#email_error").html(message.email_empty)
            } else if (message.email_less) {
                $("#email_error").html(message.email_less)
            } else {
                $("#email_error").html('');
            }

            if (message.nickname_empty) {
                $("#nickname_error").html(message.nickname_empty)
            } else if (message.nickname_less) {
                $("#nickname_error").html(message.nickname_less)
            } else {
                $("#nickname_error").html('');
            }

            if (message.surname_empty) {
                $("#surname_error").html(message.surname_empty)
            } else if (message.surname_less) {
                $("#surname_error").html(message.surname_less)
            } else {
                $("#surname_error").html('');
            }

            if (message.birth_date_empty) {
                $("#birth_date_error").html(message.birth_date_empty);
            } else if (message.birth_date_incorrect) {
                $("#birth_date_error").html(message.birth_date_incorrect);
            } else {
                $("#birth_date_error").html('');
            }

            if (message.success_update) {
                alert(message.success_update);
                location.reload(true);
            }

        })
    }

    function updatePassword(context) {
        var form = $(context)[0];
        var all_inputs = new FormData(form);
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>users/update_password",
            data: all_inputs,
            dataType: "JSON",
            contentType: false,
            processData: false
        }).done(function(message) {
            $('.csrf').val(message.csrf_hash);
            if (message.user_error) {
                alert(message.user_error);
            }
            if (message.password_current_incorrect) {
                $("#current_password_error").html(message.password_current_incorrect);
            } else {
                $("#current_password_error").html('');
            }

            if (message.password_new_mismatch) {
                $("#new_password_error").html(message.password_new_mismatch);
            } else {
                $("#new_password_error").html('');
            }

            if (message.password_new_empty) {
                $("#new_password_error").html(message.password_new_empty);
            } else {
                $("#new_password_error").html('');
            }

            if (message.password_new_less) {
                $("#new_password_error").html(message.password_new_less);
            } else {
                $("#new_password_error").html('');
            }

            if (message.success_new_password) {
                alert(message.success_new_password);
                location.reload(true);
            }
        })
    }

    function updateSecretQuestionsAndSecretAnswers(context) {
        var form = $(context)[0];
        var all_inputs = new FormData(form);
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>users/update_secret_questions_and_secret_answers",
            data: all_inputs,
            dataType: "JSON",
            contentType: false,
            processData: false
        }).done(function(message) {
            $('.csrf').val(message.csrf_hash);
            if (message.secret_question_1_empty) {
                $("#secret_question_1_error").html(message.secret_question_1_empty)
            } else {
                $("#secret_question_1_error").html('');
            }

            if (message.secret_answer_1_empty) {
                $("#secret_answer_1_error").html(message.secret_answer_1_empty)
            } else {
                $("#secret_answer_1_error").html('');
            }

            if (message.secret_question_2_empty) {
                $("#secret_question_2_error").html(message.secret_question_2_empty)
            } else {
                $("#secret_question_2_error").html('');
            }

            if (message.secret_answer_2_empty) {
                $("#secret_answer_2_error").html(message.secret_answer_2_empty)
            } else {
                $("#secret_answer_2_error").html('');
            }

            if (message.success_secret) {
                alert(message.success_secret);
                location.reload(true);
            }
        })
    }
</script>
</body>
</html>