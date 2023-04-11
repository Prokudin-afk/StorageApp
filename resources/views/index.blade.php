<!DOCTYPE html>
<html lang="en">
    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Document</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
        <script src="https://kit.fontawesome.com/6d8440bbbc.js" crossorigin="anonymous"></script>
    
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    </head>
<body>
    <ul>
        @if(isset($_COOKIE['user_role']))
            @if($_COOKIE['user_role'] =='provider')
                <li>
                    Добавить оборудование
                </li>
                <li>
                    Отчёт по оборудованию
                </li>
                <li onclick="log_out();">
                    Выйти
                </li>
            @elseif($_COOKIE['user_role'] == 'manager')
                <li>
                    Переместить оборудование
                </li>
                <li>
                    Отчёт по перемещённому оборудованию
                </li>
                <li onclick="log_out();">
                    Выйти
                </li>
            @endif
        @else
            <li onclick="$('#dvModalLogIn').modal('show');">
                Войти
            </li>
        @endif
    </ul>

<!--Modals-->
<div class="modal fade" id="dvModalLogIn" tabindex="-1" aria-labelledby="dvModalLogIn" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Авторизация</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Логин</label>
                    <input type="text" class="form-control" id="inpLogInLogin">
                </div>
                <div class="mb-3">
                    <label class="form-label">Пароль</label>
                    <input type="password" class="form-control" id="inpLogInPass">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                <button type="button" class="btn btn-primary" onclick="log_in()">Войти</button>
            </div>
        </div>
    </div>
</div>
<!--Modals-->
</body>
</html>

<script>
    $(document).ready(function() {
        //setting token for ajax
        $.ajaxSetup({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
        });
    });

    function log_in() {
        let login = $('#inpLogInLogin').val();
        let pass = $('#inpLogInPass').val();

        $.ajax({
            type: 'POST',
            url:'/logIn',
            dataType: 'json',
            data: {
                login: login,
                pass: pass
            },
            success:function(data) {
                switch(data['code']) {
                    case 101:
                        alert('Пользователь не найден');
                        break;
                    case 102: 
                        alert('Неверный пароль');
                        break;
                    case 120: 
                        alert('Успешно');
                        window.location.reload();
                        break;
                }
                console.log(data);
            },
            error: function (err) {
                if (err.status == 422) {
                    console.log(err.responseJSON);
                }
            }
        });
    }

    function log_out() {
        $.ajax({
            type: 'POST',
            url:'/logOut',
            dataType: 'json',
            success: function() {
                window.location.reload();
            }
        }); 
    }
</script>