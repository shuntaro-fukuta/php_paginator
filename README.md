###  自作ページャーライブラリ
phpの簡単なページャーライブラリです。

## 使い方
```php
<?php

require('/path/to/Paginator.php');

$items_count    = 100;
$items_per_page = 20;
$pager_count    = 5;

$paginator = new Paginator($items_count, $items_per_page, $pager_count);
$paginator->setPath('/path/to/page');
$paginator->setCurrentPage($_GET['page']);

?>

<html>
  <head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  </head>
  <body>
    <?php $paginator->render ?>
  </body>
</html>
```
Bootstrapを読み込んでいる場合、以下のような見た目になります。
![446D76E8-45A1-4C73-BCB8-4F58670E41A3_4_5005_c](https://user-images.githubusercontent.com/61815780/76425102-83081c80-63ec-11ea-8596-a6be8b39afc8.jpeg)


renderメソッドが生成するhtmlは以下のようになってます。
```html
<ul class="pagination">
  <li class="disabled">
    <a href="/path/to/page?page=1">1</a>
  </li>
  <li>
	<a href="/path/to/page?page=2">2</a>
  </li>
  <li>
	<a href="/path/to/page?page=3">3</a>
  </li>
  <li>
	<a href="/path/to/page?page=4">4</a>
  </li>
  <li>
	<a href="/path/to/page?page=5">5</a>
  </li>
  <li>
	<a href="/path/to/page?page=2">&raquo;</a>
  </li>
</ul>
```

renderメソッドを使わないでクラスやタグ等をカスタムしたい場合は下記のように使います。
```php
<?php

require('/path/to/Paginator.php');

$items_count    = 100;
$items_per_page = 20;
$pager_count    = 5;

$paginator = new Paginator($items_count, $items_per_page, $pager_count);
$paginator->setPath('/path/to/page');
$paginator->setCurrentPage($_GET['page']);

?>

<html>
  <head>
  </head>
  <body>
	<?php if ($paginator->hasPreviousPage()) : ?>
	<a href="<?php echo $paginator->createUrl($paginator->getPreviousPage()) ?>">&laquo;</a>
	<?php endif ?>

	<?php foreach $paginator->getPageNumbers() as $page ?>
	  <?php if ($paginator->isCurrentPage($page)) : ?>
	    <a href="<?php echo $paginator->createUrl($page) ?>"><?php echo $page ?></a>
	  <?php else : ?>
	    <?php echo $page ?>
	  <?php endif ?>
	<?php endforeach>

	<?php if ($paginator->hasNextPage()) : ?>
	<a href="<?php echo $paginator->createUrl($paginator->getNextPage()) ?>">&raquo;</a>
	<?php endif ?>
  </body>
</html>
```

