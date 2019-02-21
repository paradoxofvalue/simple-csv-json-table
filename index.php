<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
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
                <div class="custom-file">
                    <input type="file" name="csv" class="custom-file-input" id="csv">
                    <label class="custom-file-label" for="csv">Выберите CSV-файл</label>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Отправить</button>
        </form>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col">
            <h1>Сгенерированя таблица цен за услуги</h1>
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
        })
        
    })    
</script>
</body>
</html>