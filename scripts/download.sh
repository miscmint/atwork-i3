#! /bin/bash
  
inotifywait -mrq -e create --format %w%f /home/daniel/Downloads/ | while read FILE
do
nrOfProcesses=$(ps -aux | grep 'dolphin /home/daniel/Downloads' | wc -l)
if (($nrOfProcesses == 1)) ; then
nautilus /home/daniel/Downloads &
fi
done

exit 0
