<?php 

function __autoload($classname) {
    require_once $classname . '.php';
}

$pagesize = 10;
$query = htmlspecialchars($_GET['q']);
$page = isset($_GET['p']) && $_GET['p'] > 0 ? $_GET['p'] : 1;
$first = $pagesize * ($page - 1);
$timeelapsed = Utility::millis();
$result = Query::exec($query, $first, $pagesize);
$timeelapsed = Utility::millis() - $timeelapsed;
$paginator = new Paginator($page, $result['size'], $pagesize);
?><!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title><?php echo $query; ?> - 程序设计专题训练</title>
  <link href="http://cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">
  <link href="style.css" rel="stylesheet">
</head>

<body>
  <a href="/">
    <img class="logo" src="img/logo.png">
  </a>
  <div class="container">
    <div class="row">
      <div class="col-md-8">
        <div class="search-box">
          <form>
            <input name="q" value="<?php echo $query; ?>">
            <button><span class="glyphicon glyphicon-search"></span></button>
          </form>
        </div>
        <div class="content">
          <?php if (isset($_GET['p'])) : ?>
          <div class="search-count">
            <?php echo number_format($first + 1), ' - ', number_format(min($first + $pagesize, $result['size'])); ?>
            <?php echo ' 条结果(共 ', number_format($result['size']), ' 条)　　', $timeelapsed, ' 毫秒';?>
          </div>
          <?php else : ?>
          <div class="search-count"><?php echo $result['size']; ?> 条结果　　<?php echo $timeelapsed; ?> 毫秒</div>
          <?php endif; foreach ($result['result'] as $row) : ?>
          <div class="search-result">
            <h4 class="inline"><a href="<?php echo $row['url']; ?>"><?php echo Utility::highlight($row['title'], $result['segments']); ?></a></h4>
            <div class="caption"><?php echo Utility::highlight($row['content'], $result['segments'], true); ?></div>
            <div class="url"><?php echo $row['url']; ?></div>
          </div>
          <?php endforeach; $paginator->nav("?q=$query&p="); ?>
        </div>
      </div>
    </div>
    <hr>
  </div>
</body>

</html>
