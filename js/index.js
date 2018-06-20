var school = 1;
function addNewSchool(e) {
    school = school + 1;
    var i = parseInt(school);
    $(e.target).before("<div class='form-group'>" +
        "<label for='school_" + i + "'>Ваша школа №" + i + ": <img onclick='removeInput(this.parentElement.parentElement)' class='cross' src='/uploads/images/design_images/default.jpg'></label>" +
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
        "<label for='university_" + i + "'>Ваш университет №" + i + ": <img onclick='removeInput(this.parentElement.parentElement)' class='cross' src='/uploads/images/design_images/default.jpg'></label>" +
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
        url: "/users/insert_user",
        data: all_inputs,
        dataType: "JSON",
        contentType: false,
        processData: false
    }).done(function(messages) {
        $(".csrf").val(messages.csrf_hash);
        if (messages.email_num_rows === 1) {
            $("#email_error").html(messages.email_exist);
        } else if (messages.email_empty) {
            $("#email_error").html(messages.email_empty)
        } else if (messages.email_less) {
            $("#email_error").html(messages.email_less)
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
//            $("body").load('/publications');
            window.location.href = '/publications';
     //       location.reload(true);
        }
    })
}