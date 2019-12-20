#! /bin/bash
  
inotifywait -mrq -e create --format %w%f /home/daniel/Downloads/ | while read FILE
do
nrOfProcesses=$(ps -aux | grep 'thunar /home/daniel/Downloads' | wc -l)
if (($nrOfProcesses == 1)) ; then
thunar /home/daniel/Downloads &
fi
done
