<?php
require_once './class/AllowanceCalculator.php';

/**
 * VIEW
 * Значения по умолчанию
 */
$data = ['price' => '',
    'productName' => '',
    'finalPrice' => 0,
    'avgPrice' => 0,
    'finalAllowance' => 0,
    'priceWithAllow' => 0,];

/**
 * Если получаем в post-запросе значения price и product, то формируем массив с данными
 */
if (isset($_POST['price']) && isset($_POST['product'])) {
    $calc = new AllowanceCalculator($_POST);
    $data = $calc->getArray();
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Расчет наценки</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link href="style/style.css" rel="stylesheet">
</head>
<body>
<form method="post" action="./index.php">
    <h2>Данные для расчета</h2>
    <div class="mb-3">
        <label class="form-label">Закупочная цена в юанях:</label>
        <input class="form-control" name="price" id="price" type="text" value="<?= $data['price'] ?>" required/>
    </div>
    <div class="mb-3">
        <label class="form-label">Наименование товара:</label>
        <input class="form-control" name="product" id="product" type="text" value="<?= $data['productName'] ?>" required/>
    </div>
    <button type="submit" class="btn btn-primary" formaction="./index.php">Расчитать</button>
</form>
<div class="output">
    <h2>Рекомендация</h2>
    <table class="table table-bordered">
        <tr>
            <td>Средняя цена на "<?= $data['productName'] ?>"</td>
            <td><?= $data['avgPrice'] ?> руб.</td>
        </tr>
        <tr>
            <td>Цена входа в РФ без наценки</td>
            <td><?= $data['finalPrice'] ?> руб.</td>
        </tr>
        <tr>
            <td>Рекомендуемая наценка</td>
            <td><?= $data['finalAllowance'] ?>%</td>
        </tr>
        <tr>
            <td>Рекомендуемая цена (с учетом наценки)</td>
            <td><?= $data['priceWithAllow'] ?> руб.</td>
        </tr>
    </table>
</div>
</body>
</html>