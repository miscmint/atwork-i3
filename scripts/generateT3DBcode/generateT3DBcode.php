<?php

// example: test_template
$extensionName = $argv[1];

// example: TestTemplate
$packageName = getPackageName($extensionName);

// example: testproject/private/typo3conf/ext/test_template/
$pathToExtension = $argv[2];

// example: tx_testtemplate_domain_model_testtable
$wantedDBTable = $argv[3];

// example: testproject
$wantedProject = $argv[4];

if (array_key_exists(5, $argv)) {
    // example: testproject/private/typo3conf/ext/test_template/ext_tables.sql
    $wantedSqlFile = $argv[5];
} else {
    echo 'ERROR: Not enough information provided';
    exit;
}

// example testproject_testtable
$modelTitle = getModelTitle($wantedDBTable);

// example testproject_testtable
$tableTitle = $wantedProject . '_' . $modelTitle;

// array with all fields
$tableFields = getTableFields($wantedSqlFile, $wantedDBTable);

generateTCAFile($extensionName, $pathToExtension, $wantedDBTable, $tableTitle, $tableFields);

generateModelFile($modelTitle, $wantedProject, $packageName, $pathToExtension,$tableFields,$extensionName);

generateRepositoryFile($modelTitle, $wantedProject, $packageName, $pathToExtension);

generateLocallangFile($extensionName, $pathToExtension, $tableFields);

echo 'Done';

// TCA file
function generateTCAFile($extensionName, $pathToExtension, $wantedDBTable, $tableTitle, $tableFields) {
    $TCAFile = $pathToExtension . 'Configuration/TCA/' . $wantedDBTable .'.php';
    touch($TCAFile);

    $file = stream_get_contents(fopen('/home/daniel/.config/i3/scripts/generateT3DBcode/files/table.php', 'r'));

    $file = str_replace('EXTENSIONNAME', $extensionName, $file);
    $file = str_replace('TABLETITLE', $tableTitle, $file);
    $file = str_replace('FIRSTVARIABLE', $tableFields[0][0], $file);
    $file = str_replace('FIELDSCOMMASEPARATED', getFieldsCommaSeparated($tableFields), $file);
    $file = str_replace('DBTABLENAME', $wantedDBTable, $file);
    $file = str_replace('ADDITIONALFIELDS', getAdditionalFields($tableFields,$extensionName), $file);
    file_put_contents($TCAFile, $file);
}

// Model
function generateModelFile($modelTitle, $wantedProject, $packageName, $pathToExtension,$tableFields,$extensionName) {
    $modelFile = $pathToExtension . 'Classes/Domain/Model/' . ucfirst($modelTitle) .'.php';
    touch($modelFile);

    $file = stream_get_contents(fopen('/home/daniel/.config/i3/scripts/generateT3DBcode/files/model.php', 'r'));

    $file = str_replace('NAMESPACE', ucfirst($wantedProject) . '\\' . $packageName . '\Domain\Model', $file);
    $file = str_replace('MODELNAME', ucfirst($modelTitle), $file);
    $file = str_replace('VARIABLES', getVariables($tableFields), $file);
    $file = str_replace('GETTERSANDSETTERS', getGettersAndSetters($tableFields), $file);
    file_put_contents($modelFile, $file);
}

// Repository
function generateRepositoryFile($modelTitle, $wantedProject, $packageName, $pathToExtension) {
    $repositoryFile = $pathToExtension . 'Classes/Domain/Repository/' . ucfirst($modelTitle) .'Repository.php';
    touch($repositoryFile);

    $file = stream_get_contents(fopen('/home/daniel/.config/i3/scripts/generateT3DBcode/files/repository.php', 'r'));

    $file = str_replace('NAMESPACE', ucfirst($wantedProject) . '\\' . $packageName . '\Domain\Repository', $file);
    $file = str_replace('REPOSITORYNAME', ucfirst($modelTitle) . 'Repository', $file);
    file_put_contents($repositoryFile, $file);
}

// Locallang
function generateLocallangFile($extensionName, $pathToExtension, $tableFields) {
    $locallangFile = $pathToExtension . 'Resources/Private/Language/locallang_db.xlf';
    if (file_exists($locallangFile)) {
        $locallangFile .= '.txt';
    }
    touch($locallangFile);

    $file = stream_get_contents(fopen('/home/daniel/.config/i3/scripts/generateT3DBcode/files/locallang_db.xlf', 'r'));

    $languageData = '';
    foreach ($tableFields as $field) {
        $languageData .= '<trans-unit id="tx_' . $extensionName . '.' . $field[0] . '">
                <source>' . ucfirst($field[0]) . '</source>
            </trans-unit>
            ';
    }

    $file = str_replace('LANGUAGEDATA', $languageData, $file);
    file_put_contents($locallangFile, $file);
}


function getPackageName($extensionName) {
    $array = explode('_', $extensionName);
    return ucfirst($array[0]) . ucfirst($array[1]);
}

function getTableFields($wantedSqlFile, $wantedDBTable) {
    $file = fopen($wantedSqlFile, 'r');

    $tableFound = false; $tableFields = [];
    while (! feof($file)) {
        $line = fgets($file);

        if (strpos($line,'CREATE TABLE ' . $wantedDBTable) !== false) {
            $tableFound = true;

        } else {
            if ($tableFound) {
                if (strpos($line, ');') !== false) {
                    return $tableFields;

                } elseif (!empty($line)) {
                    $fieldInfoArray = explode(' ', trim($line, ' '));
                    $tableFields[] = [$fieldInfoArray[0], str_replace(',', '', $fieldInfoArray[1])];
                }
            }
        }
    }

    return $tableFields;
}

function getModelTitle($wantedDBTable) {
    $array = explode('_', $wantedDBTable);
    return end($array);
}

function getFieldsCommaSeparated($tableFields) {
    $returnString = '';
    foreach($tableFields as $key => $field) {
        reset($tableFields);
        // if first iteration
        if ($key === key($tableFields)) {
            $returnString .= $field[0];

        } else {
            $returnString .= ',' . $field[0];
        }
    }
    return $returnString;
}

function getAdditionalFields($tableFields,$extensionName) {
    $returnString = '';
    foreach($tableFields as $field) {
        switch (trim(preg_replace("/\s+/", " ", $field[1]))) {
            case 'varchar(255)':
                $file = stream_get_contents(fopen('/home/daniel/.config/i3/scripts/generateT3DBcode/files/fields/input.txt', 'r'));
                break;
            case 'text':
                $file = stream_get_contents(fopen('/home/daniel/.config/i3/scripts/generateT3DBcode/files/fields/textarea.txt', 'r'));
                break;
            case 'tinyint(4)':
            case 'tinyint(11)':
                $file = stream_get_contents(fopen('/home/daniel/.config/i3/scripts/generateT3DBcode/files/fields/integer.txt', 'r'));
                break;
            case 'tinyblob':
                $file = stream_get_contents(fopen('/home/daniel/.config/i3/scripts/generateT3DBcode/files/fields/image.txt', 'r'));
                break;
            default:
                $file = '';
        }
        $tempString = str_replace('EXTENSIONNAME', $extensionName, $file);
        $tempString = str_replace('FIELDNAME', $field[0], $tempString);
        $returnString .= $tempString;
    }
    return $returnString;
}

function getVariables($tableFields) {
    $returnString = '';
    foreach($tableFields as $field) {
        switch (trim(preg_replace("/\s+/", " ", $field[1]))) {
            case 'varchar(255)':
            case 'text':
                $file = stream_get_contents(fopen('/home/daniel/.config/i3/scripts/generateT3DBcode/files/types/string/variable.txt', 'r'));
                break;
            case 'int(4)':
            case 'int(11)':
            case 'tinyint(4)':
                $file = stream_get_contents(fopen('/home/daniel/.config/i3/scripts/generateT3DBcode/files/types/integer/variable.txt', 'r'));
                break;
            case 'tinyblob':
                $file = stream_get_contents(fopen('/home/daniel/.config/i3/scripts/generateT3DBcode/files/types/image/variable.txt', 'r'));
                break;
            default:
                $file = '';
        }
        $tempString = str_replace('FIELDNAMECAMELCASE', underScore2CamelCase($field[0]), $file);
        $returnString .= $tempString;
    }
    return $returnString;
}

function getGettersAndSetters($tableFields)
{
    $returnString = '';
    foreach ($tableFields as $field) {
        switch (trim(preg_replace("/\s+/", " ", $field[1]))) {
            case 'varchar(255)':
            case 'text':
                $file = stream_get_contents(fopen('/home/daniel/.config/i3/scripts/generateT3DBcode/files/types/string/getterAndSetter.txt', 'r'));
                break;
            case 'int(4)':
            case 'int(11)':
            case 'tinyint(4)':
                $file = stream_get_contents(fopen('/home/daniel/.config/i3/scripts/generateT3DBcode/files/types/integer/getterAndSetter.txt', 'r'));
                break;
            case 'tinyblob':
                $file = stream_get_contents(fopen('/home/daniel/.config/i3/scripts/generateT3DBcode/files/types/image/getterAndSetter.txt', 'r'));
                break;
            default:
                $file = '';
        }
        $tempString = str_replace('UPPERCASEFIELDNAMECAMELCASE', ucfirst(underScore2CamelCase($field[0])), $file);
        $tempString = str_replace('FIELDNAMECAMELCASE', underScore2CamelCase($field[0]), $tempString);
        $returnString .= $tempString;
    }
    return $returnString;
}

function underScore2CamelCase($string) {
    $array = explode('_', $string);
    for ($i = 1; $i < sizeof($array); $i++) {
        $array[0] .= ucfirst($array[$i]);
    }
    return $array[0];
}