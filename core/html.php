<?php
namespace Core;

class html
{
	public function __construct() {}

	public static function addInput(string $type, string $idInput, string $label = '', array $arrAttrInput = [], array $arrAttrDiv = [], mixed $inputVal = ''): string
	{
		if (!empty($inputVal)) {
			$arrAttrInput['value'] = !is_array($inputVal) ? $inputVal : ($inputVal[$idInput] ?? "");
		}

		$strAttrInput = self::adjustAttr($arrAttrInput);
		$strAttrDiv = self::adjustAttr($arrAttrDiv);
		$label = self::handleRequiredLabel($label, $arrAttrInput);

		$abreDiv = "<div id='div$idInput' $strAttrDiv>";
		$label = "<label for=".$idInput.">$label</label>";
		$input = "<input type=\"".$type."\" name=\"".$idInput."\" id=\"".$idInput."\" $strAttrInput></input>";
		$divInput = $abreDiv.$label.$input."</div>";
		return $divInput;
	}

	public static function addTable(string $id, array $arrAttrInput = []): string
	{
		$strAttrInput = self::adjustAttr($arrAttrInput);

		return $strAttrInput." id=\"".$id."\"";
	}

	private static function adjustAttr(array $arrAttr): string
	{
		$strAttrInput = '';
		if (!empty($arrAttr ?? [])) {
			foreach($arrAttr as $nmAttr => $valAttr) {
				$strAttrInput .= " $nmAttr = \"$valAttr\"";
			}
		}
		return $strAttrInput;
	}

	public static function addButton(string $type, string $id, string $label, array $arrAttrBtn = []): string
	{
		$strAttrBtn = self::adjustAttr($arrAttrBtn);
		$str = "<button $strAttrBtn type='$type' name='$id' id='$id'>$label</button>";
		return $str;
	}

	public static function addTableAction(string $type, string $id, string $label, array $arrAttrBtn = []): string
	{
		$strAttrBtn = self::adjustAttr($arrAttrBtn);
		$str = "<button $strAttrBtn type='$type' name='$id' id='$id'>$label</button>";
		return $str;
	}

	public static function addSelect(string $idInput, string $label = '', array $arrSelectOptions = [], array $arrAttrInput = [], array $arrAttrDiv = [], mixed $inputVal = ''):string
	{
		if (!empty($inputVal)) {
			$arrAttrInput['value'] = !is_array($inputVal) ? $inputVal : ($inputVal[$idInput] ?? "");
		}

		$strAttrInput = self::adjustAttr($arrAttrInput);
		$strAttrDiv = self::adjustAttr($arrAttrDiv);
		$strSelectOptions = self::handleSelectOptions($arrSelectOptions, $arrAttrInput, ($arrAttrInput['value'] ?? ""));
		$label = self::handleRequiredLabel($label, $arrAttrInput);

		$abreDiv = "<div id='div$idInput' $strAttrDiv>";
		$label = "<label for=".$idInput.">$label</label>";
		$select = "<select name=\"".$idInput."\" id=\"".$idInput."\" $strAttrInput >$strSelectOptions</select>";
		$divSelect = $abreDiv.$label.$select."</div>";
		return $divSelect;
	}

	private static function handleSelectOptions(array $arrSelectOptions, array $arrAttrInput, mixed $inputVal): string
	{
		$arrOptions = [];
		foreach($arrSelectOptions as $value => $label) {
			$selected = $value == $inputVal ? 'selected' : '';

			$arrOptions[] = "<option $selected value=".$value.">".$label."</option>";
		}

		self::addSelectOption($arrOptions, $arrAttrInput);
		return implode('', $arrOptions);
	}

	private static function addSelectOption(array &$arrSelectOptions, array $arrAttrInput): void
	{
		if (key_exists('placeholder', $arrAttrInput) && $arrAttrInput['placeholder'] == 1) {
			$arrSelectOptions = array_merge(["<option value=''>Select:</option>"], $arrSelectOptions);
		}
	}

	private static function handleRequiredLabel(string $label, array $arAttr): string
	{
		if (key_exists('required', $arAttr)) {
			$label = $label . ' <span class="span-required">*</span>';
		}

		return $label;
	}
}
