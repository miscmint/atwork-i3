#! /bin/bash

import -window root ~/Bilder/Screenshot-$(date +%F-%H:%M:%S).png

notify-send 'Screenshot wurde im Ordner "Bilder" angelegt'

thunar ~/Bilder
