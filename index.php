<link rel="stylesheet" href="bootstrap.min.css">
<style>
.custom-file-label::after {
    content: "Найти"
}
</style>
<div class="container">
    <div class="row align-items-center" style="height: 100%;">
        <form class="col align-self-center" action="upload.php" method="post" enctype="multipart/form-data">
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
<table class="table table-striped table-hover">
  <thead>
    <tr>
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

<script>
    $(document).ready(function(){
        $.get("data.json", function(data) {
            var tableDataArray = JSON.parse(data);
            tableDataArray.forEach(function(dataItem) {
                var insertHtml = '<tr>';
                insertHtml += '<td scope="row">';
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
        })
    })

    $('insertHere')


    
</script>
