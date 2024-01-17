#! /bin/bash

nautilus $((find ~/Downloads -maxdepth 0 && find ~/Documents -type d) | dmenu -i -m 1 -fn monospace -l 100)

exit 0
