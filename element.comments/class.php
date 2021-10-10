<?php
use \Bitrix\Main\Loader;
use \Bitrix\Main\Application;

if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

class ElementComments extends CBitrixComponent {

	public function __construct($component = null) {
		parent::__construct($component);
	}

	public function onPrepareComponentParams($arParams) {		
		$arParams["CACHE_TIME"] = $arParams["CACHE_TIME"] | 28800;
		
		return $arParams;
	}

	private static function checkRequiredFields($required, $response) {

		$errFields = [];
		if(!empty($required)) {

			foreach($response as $key => $value) 
				if((in_array($key, $required)) && ($value == ""))
					$errFields[] = $key;

		}

		return $errFields;
	}

	public function executeComponent() {

		# исходящие данные для шаблона
		$this->arResult["COMMENTS"] = [];
		$this->arResult["FIELDS_ERROR"] = [];
		$this->arResult["ADD_COMMENT"] = "";


		CModule::IncludeModule("iblock");

		if(empty($this->arParams['IBLOCK_ID']) || empty($this->arParams['ELEMENT_ID']) || empty($this->arParams['IBLOCK_TYPE'])) return;

		# проверка типа хранения данных
		$validStorage = ["IBLOCK","FILE","TABLE"];
		$storage = (empty($this->arParams['IBLOCK_ID']) || !in_array($this->arParams['IBLOCK_ID'],$validStorage)) ? "IBLOCK" : $this->arParams['IBLOCK_ID'];

		# проверка почты
		$email = (isset($_REQUEST["EMAIL"])) ? filter_var($_REQUEST['EMAIL'], FILTER_VALIDATE_EMAIL) : false;

		$arParams['EMAIL'] = ($email !== false) ? $email : '';
		$arParams['FIO'] = (isset($_REQUEST["FIO"])) ? trim($_REQUEST["FIO"]) : '';
		$arParams['TEXT'] = (isset($_REQUEST["TEXT"])) ? trim($_REQUEST["TEXT"]) : '';


		# кэш
		$key = "element.comments".$this->arParams['IBLOCK_ID'].$this->arParams['ELEMENT_ID'].$_SERVER['QUERY_STRING'];
		$uniq_str = sha1($key);
		$cache = Bitrix\Main\Data\Cache::createInstance();

		# если добавлен новый комментарий
		$submit = (isset($_REQUEST['SUBMIT'])) ? true : false;
		if($submit) {
			# проверяем, что все обязательные поля заполнены
			$this->arResult["FIELDS_ERROR"] = self::checkRequiredFields($this->arParams['REQUIRED_FIELDS'],$arParams);
			# почистим кэш, так как у нас обновился список комментариев
			$cache->CleanDir('/element.comments/'.$this->arParams['ELEMENT_ID'].'/', '/cache/');
		}


		$IBLOCK_ID = intval($this->arParams['STORAGE_PROPERTY']);
		$ELEMENT_ID = intval($this->arParams['ELEMENT_ID']);

		# добавление нового комментария
		if($IBLOCK_ID && $ELEMENT_ID && $submit && empty($this->arResult["FIELDS_ERROR"])) {

			$PROP = [];
			$PROP["FIO"] = $arParams['FIO'];
			$PROP["EMAIL"] = $arParams['EMAIL'];
			$PROP["TEXT"] = $arParams['TEXT'];
			$PROP["ELEMENT_ID"] = $ELEMENT_ID;

			global $USER;
			$element = new \CIBlockElement;
			$addElementId = $element->Add(
				array(
				  "MODIFIED_BY"    => $USER->GetID(),
				  "IBLOCK_SECTION_ID" => false,
				  "IBLOCK_ID"      => $IBLOCK_ID,
				  "CODE" => $this->arParams['IBLOCK_TYPE'],
				  "PROPERTY_VALUES"=> $PROP,
				  "NAME"           => $arParams['FIO'],
				  "ACTIVE"         => "Y",
				  "PREVIEW_TEXT"   => "",
				  "DETAIL_TEXT"    => ""
			));

			if($addElementId)
				$this->arResult["ADD_COMMENT"] = $addElementId;
			else
				$this->arResult["ADD_COMMENT"]['ERROR'] = $element->LAST_ERROR;

		}

		# отдаем кэш или результат выборки
		if($cache->initCache($this->arParams['CACHE_TIME'], $uniq_str, '/element.comments/'.$this->arParams['ELEMENT_ID'].'/', '/cache/')) {
			$this->arResult = $cache->getVars();
		} elseif ($cache->startDataCache()) {

			if($submit)
				$cache->abortDataCache();

			switch ($storage) {

				case "IBLOCK":

					$resComments = \CIBlockElement::GetList(
						array("ID"=>"DESC"), 
						array(
							'IBLOCK_ID'					=> $IBLOCK_ID,
							'ACTIVE'					=> 'Y',
							'PROPERTY_ELEMENT_ID'		=> $ELEMENT_ID,
						), false, false, array(
							'XML_ID',
							'ID',
							'NAME',
							'DATE_CREATE',
							'PROPERTY_FIO',
							'PROPERTY_EMAIL',
							'PROPERTY_TEXT'
					));

					while($comment = $resComments->GetNext()) 
						$this->arResult["COMMENTS"][] = $comment;


				break;
	
				case "FILE":
					// TODO
				break;
	
				case "TABLE":
					// TODO
				break;

			}


			$cache->endDataCache($this->arResult);
		}

		
		$this->includeComponentTemplate();
	}

}
