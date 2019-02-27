<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="robots" content="noindex, nofollow">
    <meta http-equiv="Cache-Control" content="no-cache">
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="https://static.tildacdn.com/tild6165-3230-4334-a338-333131383566/logo_S.ico" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Сервисный центр Apple - Обновление сервисов</title>
    <link rel="stylesheet" href="bootstrap.min.css">
    <style>
        body {
            background-color: rgba(54, 54, 54, 0.08);
        }
        .custom-file-label::after {
            content: "Найти"
        }
        .logo {
            height: 50px;
            margin: 0 auto;
        }
        .filter-table .quick {
            margin-left: 1em;
            padding: 5px 0;
            font-size: 1em;
            text-decoration: none;
            text-transform: uppercase;
        }
        .file-upload-wrapper {
        position: relative;
        width: 100%;
        height: 60px;
        }
        .file-upload-wrapper:after {
        content: attr(data-text);
        font-size: 18px;
        position: absolute;
        top: 0;
        left: 0;
        background: #fff;
        padding: 10px 15px;
        display: block;
        width: calc(100% - 40px);
        pointer-events: none;
        z-index: 20;
        height: 40px;
        line-height: 40px;
        color: #999;
        border-radius: 5px 10px 10px 5px;
        font-weight: 300;
        box-sizing: content-box;
        }
        .file-upload-wrapper:before {
        content: "Загрузить";
        position: absolute;
        top: 0;
        right: 0;
        display: inline-block;
        height: 60px;
        background: #4daf7c;
        color: #fff;
        font-weight: 700;
        z-index: 25;
        font-size: 16px;
        line-height: 60px;
        padding: 0 15px;
        text-transform: uppercase;
        pointer-events: none;
        border-radius: 0 5px 5px 0;
        }
        .file-upload-wrapper:hover:before {
        background: #3d8c63;
        }
        .file-upload-wrapper input {
        opacity: 0;
        box-sizing: content-box;
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        z-index: 99;
        height: 40px;
        margin: 0;
        padding: 0;
        display: block;
        cursor: pointer;
        width: 100%;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row align-items-center" style="height: 100vh;">
        <form class="col align-self-center" action="upload.php" method="post" enctype="multipart/form-data">
            <img src="logo.png" class="logo" alt="">
            <h1>Сервисный центр Apple — ремонт iPhone, iPad и MacBook.</h1>
            <p>Обновление прайсов</p>
            <div class="input-group mb-3">
                <div class="file-upload-wrapper" data-text="Выберите CSV-файл!">
                    <input type="file" name="csv" class="file-upload-field" id="csv">
                </div>
            </div>
            <!-- <button type="submit" class="btn btn-primary">Отправить</button> -->

            <p style="margin-top: 100px">Исходный файл: <a target="_blank" href="https://docs.google.com/spreadsheets/d/1lg_993r5LItZ17PkDpA8X7Atn7GFMWcFFRFNpuhT38E/export?format=csv&id=1lg_993r5LItZ17PkDpA8X7Atn7GFMWcFFRFNpuhT38E&gid=0">CSV</a></p>
            <p>Исходная таблица: <a target="_blank" href="https://docs.google.com/spreadsheets/d/1lg_993r5LItZ17PkDpA8X7Atn7GFMWcFFRFNpuhT38E/edit?usp=sharing">Таблица</a></p>
        </form>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col">
            <h1><a id="table"></a>Сгенерированя таблица цен за услуги</h1>
            <table class="table table-striped table-hover">
            <thead>
                <tr>
                <th scope="col">Модель</th>
                <th scope="col">Услуги</th>
                <th scope="col">Гарантия</th>
                <th scope="col">Стоимость, грн</th>
                </tr>
            </thead>
            <tbody class="insertHere">
                
            </tbody>
            </table>
        </div>
    </div>
</div>
<script src="jquery-3.3.1.min.js"></script>
<script src="bootstrap.min.js"></script>
<script src="https://rawgit.com/bgrins/bindWithDelay/master/bindWithDelay.js"></script>
<script src="https://rawgit.com/sunnywalker/jQuery.FilterTable/master/jquery.filtertable.min.js"></script>
<script>
    $(document).ready(function(){
        $("form").on("change submit", ".file-upload-field", function(event){
            event.preventDefault();
            var input = $('.file-upload-field')[0];
            $(this).parent(".file-upload-wrapper").attr("data-text", $(this).val().replace(/.*(\/|\\)/, ''));
            if (input.files.length && $.inArray(input.files[0].type, ['text/csv', 'application/vnd.ms-excel'])) {
                var fileData = input.files[0];
                var formData = new FormData();
                formData.append('csv', fileData);
                $.ajax({
                    type: "POST",
                    url: "upload.php",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        getData();
                        alert(response.message);
                    },
                    error: function (response) {
                        response = JSON.parse(response.responseText);
                        alert(response.message);
                    }
                });
            } else {
                alert('Используйте пожалуйста, корректный файл.')
            }
        });
        getData(()=>{
            console.log('Initialized!');
        });        
    })

    function clearTable() {
        $('.insertHere').html('');
    }

    function getData(callback) {
        clearTable();
        $.get("data.json", function(data) {
            var tableDataArray = JSON.parse(data);
            tableDataArray.forEach(function(dataItem) {
                var insertHtml = '<tr>';
                insertHtml += '<td scope="row">';
                insertHtml += dataItem.Model;
                insertHtml += '</td>';
                insertHtml += '<td>';
                insertHtml += dataItem.Service;
                insertHtml += '</td>';
                insertHtml += '<td>';
                insertHtml += dataItem.Warranty ? dataItem.Warranty + ' мес.' : '-';
                insertHtml += '</td>';
                insertHtml += '<td>';
                insertHtml += dataItem.Price ? dataItem.Price : 'бесплатно';
                insertHtml += '</td>';
                insertHtml += '</tr>';
                $(insertHtml).appendTo('.insertHere');
            })
            $('table').filterTable({ // apply filterTable to all tables on this page
                quickList: ['Ремонт', 'Аккумулятор', 'Дигностика', 'Apple'], // add some shortcut searches
                //quickListClear: '&times; Очистить' // add the clear filter item
                label: ' ',
                placeholder: 'Фильтр таблицы: введите название услуги'
            });
            callback ? callback() : setTimeout(function(){window.location.href = 'https://stuff.ipartner-smm.com.ua/apple-club-remont/services.php#table'}, 200);
        })
    }  
</script>
</body>
</html>