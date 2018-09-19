<!DOCTYPE html>
<html>
<head>
    <title>New Line kg</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="shortcut icon" href="<?php echo base_url()?>uploads/icons/logo.png">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/index.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/animate.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/media.css">
</head>
<body>
<div class="container">
    <div class="col-md-12 col-lg-6">
        <img class="img-thumbnail newline-huge huge-hidden" src="<?php echo base_url()?>uploads/icons/newline.png">
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#authorization">Вход</a></li>
            <li><a data-toggle="tab" href="#registration">Регистрация</a></li>
        </ul>
        <div class="tab-content">

            <div id="authorization" class="tab-pane fade in active">
                <form method="post" action="<?php echo base_url()?>users/authorization">
                    <input class="csrf" type="hidden" name="csrf_test_name" value="<?php echo $csrf_hash?>">
                    <div class="form-group">
                        <label>Email:</label>
                        <input type="text" class="form-control" placeholder="Введите email" name="email">
                    </div>
                    <div class="form-group">
                        <label>Пароль:</label>
                        <input type="password" class="form-control" placeholder="Введите пароль" name="password">
                    </div>
                    <div class="form-group">
                        <a href="<?php echo base_url()?>restore">Забыли пароль?</a>
                    </div>
                    <button type="submit" class="btn btn-primary center-block">Войти</button>
                </form>
            </div>

            <div id="registration" class="tab-pane fade">
                <form action="javascript:void(0)" onsubmit="registrationUser(this)" enctype="multipart/form-data">
                    <input class="csrf" type="hidden" name="csrf_test_name" value="<?php echo $csrf_hash?>">
                    <div class="form-group">
                        <label for="email">Ваш email:</label>
                        <input type="text" class="form-control common_input" id="email" placeholder="Введите email" name="email">
                        <div id="email_error" class="error"></div>
                    </div>
                    <div class="form-group">
                        <label for="password">Ваш пароль:</label>
                        <input type="password" class="form-control common_input" id="password" placeholder="Введите пароль" name="password">
                        <div id="password_error" class="error"></div>
                    </div>
                    <div class="form-group">
                        <label for="check_password">Повтор Вашего пароля:</label>
                        <input type="password" class="form-control common_input" id="check_password" placeholder="Повторите пароль" name="check_password">
                    </div>
                    <div class="form-group">
                        <label for="nickname">Ваше имя:</label>
                        <input type="text" class="form-control common_input" id="nickname" placeholder="Введите имя" name="nickname">
                        <div id="nickname_error" class="error"></div>
                    </div>
                    <div class="form-group">
                        <label for="surname">Ваша фамилия:</label>
                        <input type="text" class="form-control common_input" id="surname" placeholder="Введите фамилию" name="surname">
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
                        <input type="radio" name="gender" value="Мужчина">Мужчина
                        <input type="radio" name="gender" value="Женщина">Женщина
                        <input type="radio" name="gender" value="Скрыто">Скрыто<br>
                        <div id="gender_error" class="error"></div>
                    </div>
                    <div class="form-group">
                        <label>Ваш секретный вопрос №1</label><br>
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
                        <label for="secret_answer_1">Ваш ответ на 1 вопрос:</label>
                        <input type="text" class="form-control common_input" id="secret_answer_1" placeholder="Введите ответ на 1 вопрос" name="secret_answer_1">
                        <div id="secret_answer_1_error" class="error"></div>
                    </div>
                    <div class="form-group">
                        <label>Ваш секретный вопрос №2</label><br>
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
                        <label for="secret_answer_2">Ваш ответ на 2 вопрос:</label>
                        <input type="text" class="form-control common_input" id="secret_answer_2" placeholder="Введите ответ на 2 вопрос" name="secret_answer_2">
                        <div id="secret_answer_2_error" class="error"></div>
                    </div>

                    <a href="#optional" class="btn btn-warning" data-toggle="collapse">Дополнительные поля</a><br>
                    <div id="optional" class="collapse">
                        <div class="form-group">
                            <label for="image">Ваша аватарка:</label>
                            <input type="file" class="form-control common_input" id="image" name="image">
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
                    </div>

                    <button type="submit" class="btn btn-primary center-block">Зарегистрироваться</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-6 hedgehog small-hidden middle-hidden big-hidden">
        <img class="hedgehog-logo" src="<?php echo base_url()?>uploads/icons/logo.png"><br>
        <img class="newline-logo" src="<?php echo base_url()?>uploads/icons/newline.png"><br>
    </div>
</div>

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

    function registrationUser (context) {
        var form = $(context)[0];
        var all_inputs = new FormData(form);
        $.ajax({
            method: "POST",
            url: "<?php echo base_url()?>" + "users/insert_user",
            data: all_inputs,
            dataType: "JSON",
            contentType: false,
            processData: false
        }).done(function (messages) {
            $(".csrf").val(messages.csrf_hash);
            if (messages.email_num_rows === 1) {
                $("#email_error").html(messages.email_exist);
            } else if (messages.email_empty) {
                $("#email_error").html(messages.email_empty)
            } else if (messages.email_less) {
                $("#email_error").html(messages.email_less)
            } else if (messages.email_regex) {
                $("#email_error").html(messages.email_regex)
            } else {
                $("#email_error").html('');
            }

            if (messages.password_mismatch) {
                $("#password_error").html(messages.password_mismatch)
            } else if (messages.password_empty) {
                $("#password_error").html(messages.password_empty)
            } else if (messages.password_less) {
                $("#password_error").html(messages.password_less)
            } else {
                $("#password_error").html('');
            }

            if (messages.nickname_empty) {
                $("#nickname_error").html(messages.nickname_empty)
            } else if (messages.nickname_less) {
                $("#nickname_error").html(messages.nickname_less)
            } else {
                $("#nickname_error").html('');
            }

            if (messages.surname_empty) {
                $("#surname_error").html(messages.surname_empty)
            } else if (messages.surname_less) {
                $("#surname_error").html(messages.surname_less)
            } else {
                $("#surname_error").html('');
            }


            if (messages.birth_date_empty) {
                $("#birth_date_error").html(messages.birth_date_empty);
            } else if (messages.birth_date_incorrect) {
                $("#birth_date_error").html(messages.birth_date_incorrect);
            } else {
                $("#birth_date_error").html('');
            }

            if (messages.gender_empty) {
                $("#gender_error").html(messages.gender_empty)
            } else {
                $("#gender_error").html('');
            }

            if (messages.secret_question_1_empty) {
                $("#secret_question_1_error").html(messages.secret_question_1_empty)
            } else {
                $("#secret_question_1_error").html('');
            }

            if (messages.secret_answer_1_empty) {
                $("#secret_answer_1_error").html(messages.secret_answer_1_empty)
            } else {
                $("#secret_answer_1_error").html('');
            }

            if (messages.secret_question_2_empty) {
                $("#secret_question_2_error").html(messages.secret_question_2_empty)
            } else {
                $("#secret_question_2_error").html('');
            }

            if (messages.secret_answer_2_empty) {
                $("#secret_answer_2_error").html(messages.secret_answer_2_empty)
            } else {
                $("#secret_answer_2_error").html('');
            }

            if (messages.success_registration) {
                window.location.href = "<?php echo base_url()?>publications";
            }
        });
    }
    window.onload = function () {
        document.getElementsByClassName('newline-logo')[0].classList.add('logo-animated');
    };
</script>
</body>
</html>