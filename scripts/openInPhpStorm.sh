#! /bin/bash

projects=$(find $1 -maxdepth 1 -mindepth 1 -type d)

results=()
for project in $projects
do
	path=$project/

	idea=$(find $path -maxdepth 1 -type d -name ".idea" -printf '%h\n' 2>/dev/null)

        if [ $idea ]
        then
                results+=($idea)
        fi

done

projects=$(find /tmp -maxdepth 1 -mindepth 1 -type d)

for project in $projects
do
	if [[ ! $project = /tmp/.* ]] && [[ ! $project = /tmp/PHP* ]] && [[ ! $project = CS ]] && [[ ! $project = Fixertemp* ]] && [[ ! $project = /tmp/systemd* ]]
	then
		results+=($project)
	fi
done

projectPath=$(printf '%s\n' "${results[@]}" | dmenu -i -m 1 -fn monospace -l 100)

exec ~/phpstorm/bin/phpstorm.sh $projectPath

exit 0
