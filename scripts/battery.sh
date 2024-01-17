#! /bin/bash

percentage=$(upower -i /org/freedesktop/UPower/devices/battery_BAT0 | grep percentage | awk '{print $2}')
percentageInt=$(echo $percentage | tr -dc '0-9')

state=$(upower -i /org/freedesktop/UPower/devices/battery_BAT0 | grep state | awk '{print $2}')

if [ $state == 'discharging' ]
then
	info=$(upower -i /org/freedesktop/UPower/devices/battery_BAT0 | grep 'time to empty' | awk {'printf ("(%s %s )", $4, $5)'})
else
	info=$(upower -i /org/freedesktop/UPower/devices/battery_BAT0 | grep 'time to full' | awk {'printf ("(%s %s )", $4, $5)'})
fi

if [ $percentageInt -lt 20 ]
then
	if [ $state == 'discharging' ]
	then
		notify-send -u critical 'Low battery status'
	fi
	symbol=

elif [ $percentageInt -lt 30 ]
then
	symbol=

elif [ $percentageInt -lt 60 ]
then
	symbol=

elif [ $percentageInt -lt 90 ]
then
	symbol=
else
	symbol=
fi

echo $symbol $percentage $info

exit 0
