#! /bin/bash

RETURN=$(umount /media/daniel/* 2>&1 >/dev/null) 

if [ -z "$RETURN" ]; then
	notify-send -t 3000 'Unmount erfolgreich'

else
	notify-send -t 3000 -u critical 'Unmount nicht möglich, wahrscheinlich wird ein Gerät noch verwendet oder alle Geräte sind schon entfernt.'
fi

exit 0
