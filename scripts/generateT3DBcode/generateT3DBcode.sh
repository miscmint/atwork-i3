#! /bin/bash

wantedProject="$(ls $1 | dmenu -p 'Project:' -m 0 -fn monospace -l 100)"

if [ -z "$wantedProject" ]
then
  notify-send 'No project selected'
  exit 0
fi

wantedSqlFile="$(find $1$wantedProject -type d -name "typo3" -prune -o -name ext_tables.sql 2>/dev/null | tr ' ' '\n' | sed '/3$/d' | dmenu -p 'SQL file:' -m 0 -fn monospace -l 100)"

if [ -z "$wantedSqlFile" ]
then
  notify-send 'No ext_tables.sql file selected'
  exit 0
fi

# the DB table for which code should be generated
wantedDBTable=$(sed -n -e '/^CREATE TABLE/p' "$wantedSqlFile" | tr 'CREATE TABLE ' ' ' | sed 's/..$//' | dmenu -p 'DB table:' -m 0 -fn monospace -l 100)

if [ -z "$wantedDBTable" ]
then
  notify-send 'No SQL table selected'
  exit 0
fi

wantedDBTable=$(echo $wantedDBTable | awk '{$1=$1};1')

pathToExtension="${wantedSqlFile/ext_tables.sql/''}"

extensionName=$(basename "$(echo $pathToExtension | sed 's/.$//')")

# generate Model Directory
generateModel() {
	if [ ! -d "$pathToExtension/Classes" ];
	then
		mkdir "$pathToExtension/Classes"
	fi

	if [ ! -d "$pathToExtension/Classes/Domain" ];
	then
		mkdir "$pathToExtension/Classes/Domain"
	fi

	if [ ! -d "$pathToExtension/Classes/Domain/Model" ];
	then
		mkdir "$pathToExtension/Classes/Domain/Model"
	fi
}

generateModel

# generate Repository Directory
generateRepository() {
	if [ ! -d "$pathToExtension/Classes/Domain/Repository" ];
  then
    mkdir "$pathToExtension/Classes/Domain/Repository"
  fi
}

generateRepository

notify-send "$(php -f ~/.config/i3/scripts/generateT3DBcode/generateT3DBcode.php -- $extensionName $pathToExtension $wantedDBTable $wantedProject $wantedSqlFile)"
