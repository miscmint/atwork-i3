#! /bin/bash

dolphin $((find ~/Bilder -maxdepth 0 && find ~/Downloads -maxdepth 0 && find ~/Documents -type d) | dmenu -i -m 0 -fn monospace -l 100)

exit 0
