#! /bin/bash

import -window root /tmp/screenshot-$(date +%F-%H:%M:%S).png

notify-send 'Screenshot wurde im Ordner "/tmp" angelegt'

nautilus /tmp &
#dolphin /tmp &
#thunar /tmp &

exit 0
