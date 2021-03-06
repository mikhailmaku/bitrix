# bitrix
bitrix component

Компонент для добавления комментариев к элементам инфоблока.

Проверка компонента проводилась на Bitrix 15.5.10       
В данный момент компонент поддерживает сохранение комментариев в элементах отдельного инфоблока. 

Для установки компонента необходимо:
1. создать новый инфоблок
2. добавить для инфоблока свойства:

Название     | Тип           | Код
----------------------------------------------------
ФИО            | Строка       | FIO

EMAIL          | Строка       | EMAIL

Текст           | HTML/текст | TEXT

ID элемента  | Число        | ELEMENT_ID


3. инициализировать компонент на странице, например:

```php
<?require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');?>

<?
$APPLICATION->ShowHead();
$APPLICATION->IncludeComponent(
	"element.comments", 
	".default", 
	array(
		"IBLOCK_TYPE" => "EVENTS", // код инфоблока, к элементам которого будут добавляться комментарии
		"IBLOCK_ID" => "10", // ID инфоблока, к элементам которого будут добавляться комментарии
		"ELEMENT_ID" => "2271", // ID элемента к которому будут добавляться комментарии
		"STORAGE" => "IBLOCK", // тип хранилища (сейчас реализован один тип IBLOCK, но возможно добавление еще нескольких типов: FILE и TABLE)
		"STORAGE_PROPERTY" => "47", // ID инфоблока в элементы которого добавдяются комментарии
		"REQUIRED_FIELDS" => array( // обязательные к заполнению поля
			0 => "FIO",
			1 => "EMAIL",
		),
		"COMPONENT_TEMPLATE" => ".default",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "3600"
	),
	false
);
?>
```

![пример работы компонента](https://github.com/mikhailmaku/bitrix/blob/main/bitrix.component.comments.png?raw=true)
