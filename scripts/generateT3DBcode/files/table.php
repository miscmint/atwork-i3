<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

return [
    'ctrl' => [
        'title' => 'LLL:EXT:EXTENSIONNAME/Resources/Private/Language/locallang_db.xlf:TABLETITLE',
        'label' => 'FIRSTVARIABLE',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'dividers2tabs' => TRUE,
        'delete' => 'deleted',
        'sortby' => 'sorting',
        'shadowColumnsForNewPlaceholders' => 'sys_language_uid,l10n_parent',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'languageField' => 'sys_language_uid',
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
        'iconfile' => 'EXT:EXTENSIONNAME/Resources/Public/Icons/sudhaus7.svg',
        'searchFields' => 'FIELDSCOMMASEPARATED',
    ],
    'interface' => [
        'showRecordFieldList' => 'hidden,FIELDSCOMMASEPARATED',
    ],
    'types' => [
        '1' => ['showitem' => 'hidden,FIELDSCOMMASEPARATED'],
    ],
    'palettes' => [
        '1' => ['showitem' => 'FIELDSCOMMASEPARATED'],
    ],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.language',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'special' => 'languages',
                'items' => [
                    [
                        'LLL:EXT:lang/locallang_general.xlf:LGL.allLanguages',
                        -1,
                        'flags-multiple'
                    ],
                ],
                'default' => 0,
            ]
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['', 0],
                ],
                'foreign_table' => 'DBTABLENAME',
                'foreign_table_where' => 'AND DBTABLENAME.pid=###CURRENT_PID### AND DBTABLENAME.sys_language_uid IN (-1,0)',
            ]
        ],
        'l10n_diffsource' => [
            'config'=> [
                'type'=>'passthrough']
        ],
        'hidden' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.hidden',
            'config' => [
                'type' => 'check',
            ],
        ],
	ADDITIONALFIELDS
    ],
];
