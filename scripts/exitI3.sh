#! /bin/bash

printf 'Nein\nJa' | dmenu -i -m 0 -fn monospace -l 100 -sb '#f49700' -sf '#ffffff' -nb '#000000' -nf '#ce7f00' -p 'Wirklich i3 verlassen?'
