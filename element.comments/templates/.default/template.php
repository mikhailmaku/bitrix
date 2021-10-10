<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<?
$this->addExternalCss($templateFolder."/css/bootstrap.min.css");
$this->addExternalCss($templateFolder."/css/style.css");
$this->addExternalJS($templateFolder."/js/bootstrap.min.js");
?>

<div class="comments-container">

<form method="POST">
  <div class="form-group">
    <label for="email">E-mail</label>
    <input type="email" class="form-control" id="email" aria-describedby="email" placeholder="mail@mail.ru" name="EMAIL" />
  </div>
  <div class="form-group">
    <label for="fio">ФИО</label>
	  <input type="text" class="form-control" id="fio" placeholder="Иванов Иван Иванович" name="FIO" />
  </div>
  <div class="form-group">
    <label for="text">Комментарий</label>
    <textarea class="form-control" id="text" rows="3" name="TEXT"></textarea>
  </div>
  <button type="submit" class="btn btn-primary" name="SUBMIT">Отправить</button>
</form>


<?
if(!empty($arResult["FIELDS_ERROR"])) {

	foreach($arResult["FIELDS_ERROR"] as $fild) {
		?>
		<div class="p-3 mb-2 bg-danger text-white">Поле <?=$fild?> обязательно для заполнения</div>
		<?
	}
} 

if(is_array($arResult["ADD_COMMENT"])) {
	?>
	<div class="p-3 mb-2 bg-danger text-white">Произошла ошибка при добавлении комментария <?=$arResult["ADD_COMMENT"]["ERROR"]?></div>
	<?
} elseif(intval($arResult["ADD_COMMENT"])) {
	?>
		<div class="p-3 mb-2 bg-success text-white">Комментарий #<?=$arResult["ADD_COMMENT"]?> успешно добавлен</div>
	<?
}


$numCommentsPage = 5;
if (!empty($arResult["COMMENTS"])) {

	$rs = new CDBResult;
	$rs->InitFromArray($arResult["COMMENTS"]);
	$rs->NavStart($numCommentsPage);

	if($rs->IsNavPrint())
	{
		while ($arItem = $rs->Fetch()) {
			?>
			<div class="comment">
			  <h3>Комментарий #<?=$arItem["ID"]?></h3>
			  <p><small class="text-muted">Дата и время добавления: <?=$arItem["DATE_CREATE"]?></small></p>
			  <div class="form-group">
				<label for="email">E-mail</label>
				<input type="text" readonly class="form-control" id="email" value="<?=$arItem["PROPERTY_EMAIL_VALUE"]?>" />
			  </div>
			  <div class="form-group">
				<label for="fio">Фамилия Имя Отчетство</label>
				  <input type="text" readonly class="form-control" id="fio" name="FIO" value="<?=$arItem["PROPERTY_FIO_VALUE"]?>" />
			  </div>
			  <div class="form-group">
				<label for="text">Комментарий</label>
				<textarea readonly class="form-control" id="text" rows="3" name="TEXT"><?=$arItem["PROPERTY_TEXT_VALUE"]["TEXT"]?></textarea>
			  </div>
			</div>
			<?
		}

		$rs->NavPrint("Комментарии", false, "text", false);

	}
} else {
?>
	<div class="comment">
		<h3>Комментарии отсутствуют</h3>
	</div>
<?
}

?>
</div>