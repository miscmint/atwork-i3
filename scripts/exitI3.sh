#! /bin/bash

choice=$(printf 'Nein\nJa' | dmenu -i -m 0 -fn monospace -l 100 -sb '#ed9216' -sf '#000000' -p 'Wirklich i3 verlassen?         ')

if [ "$choice" == "Ja" ]
then
        exec i3-msg exit
fi
