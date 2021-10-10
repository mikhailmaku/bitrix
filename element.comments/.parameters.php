<?
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentParameters = array(
	"GROUPS" => array(
	),
	"PARAMETERS"  =>  array(
		"IBLOCK_TYPE"  =>  array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("T_IBLOCK_TYPE"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
		),
       "IBLOCK_ID"  =>  array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("T_IBLOCK_ID"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
		),
       "ELEMENT_ID"  =>  array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("T_ELEMENT_ID"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
		),
       "STORAGE"  =>  array(
			"PARENT" => "DATA_SOURCE",
			"NAME" => GetMessage("T_STORAGE"),
			"TYPE" => "LIST",
			"VALUES" => array(
				"IBLOCK"=>"IBLOCK",
				"FILE"=>"FILE",
				"TABLE"=>"TABLE",
			),
			"DEFAULT" => "",
		),
       "STORAGE_PROPERTY"  =>  array(
			"PARENT" => "DATA_SOURCE",
			"NAME" => GetMessage("T_STORAGE_PROPERTY"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
		),
       "REQUIRED_FIELDS"  =>  array(
			"PARENT" => "VISUAL",
			"NAME" => GetMessage("T_REQUIRED_FIELDS"),
			"TYPE" => "LIST",
			"ADDITIONAL_VALUES" => "N",
			"MULTIPLE" => "Y",
			"VALUES" => array(
				"FIO"=>"FIO",
				"EMAIL"=>"EMAIL",
				"TEXT"=>"TEXT",
			),
			"DEFAULT" => "",
		),
		'CACHE_TIME' => ['DEFAULT' => 3600],
	),
);
?>