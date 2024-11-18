<?php

namespace Infuse\DataProcessor\Core;

use Bitrix\Main\UserTable;
class MainDataProcessor
{
    /**
     * Returns all the vowels from the user’s NAME, LAST_NAME, and SECOND_NAME
     * in lowercase without spaces, corresponding to the parameter.
     *
     * @param int $userID
     * @return string|array
     */
    public function getUserNameVowels(int $USER_ID)
    {

        if (empty($USER_ID) || !is_numeric($USER_ID) || $USER_ID <= 0) {
            return ['error' => 'USER_ID is empty or invalid data'];
        }

        // Получаем данные пользователя
        $userResult = UserTable::getList([
            'filter' => ['ID' => $USER_ID],
            'select' => ['NAME', 'SECOND_NAME', 'LAST_NAME']
        ]);

        $arUserDB = $userResult->fetch();

        if (!$arUserDB) {
            return ['error' => 'User not found'];
        }

        // Объединяем имя, отчество и фамилию без пробелов и приводим к нижнему регистру
        $fullName = str_replace(' ', '', $arUserDB['NAME'] . $arUserDB['SECOND_NAME'] . $arUserDB['LAST_NAME']);
        $fullName = mb_strtolower($fullName, 'UTF-8');

        // Список гласных букв для разных языков
        $vowels = 'aeiouyаеёиоуыэюяäöüéèêíóúáàâõàèìòùıİąęóåøæìïîôûëèçãñāēīōūÿůőűěůêôâáíúçăîșțąęįųū';

        // Создаем шаблон регулярного выражения
        $pattern = '/[' . preg_quote($vowels, '/') . ']/u';

        // Извлекаем гласные буквы
        preg_match_all($pattern, $fullName, $matches);

        // Объединяем найденные гласные в одну строку
        $userNameVowelsString = implode('', $matches[0]);

        return $userNameVowelsString;
    }

}
