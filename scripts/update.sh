#! /bin/bash

TEXT=`cat ~/.scripts/files/update.txt`

#echo $TEXT

# -z tests for a zero-length string
if [ -z "$TEXT" ]; then
	`sudo apt update`
	echo 'text' > ~/.scripts/files/update.txt
	echo ''
else
	#echo 'not empty'
	#echo '' > ~/.scripts/files/update.txt
fi

exit 0
