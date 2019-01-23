#!/bin/sh
chmod a+r *
chmod a+rx ZoomEngine*
chmod a+rw default.zcfg
chmod a+rw .ZoomLogBuffer
chmod a+rw .ZoomPageDataBuffer
chmod a+rw .key.tmp
chmod a+rw userinfo.dat
chmod -R a+rx plugins
chmod a+rx help
chmod -R a+r help
chmod a+rx images
chmod -R a+r images
chmod a+rx lang
chmod a+rx extras
chmod a+rx scripts
chmod -R a+r lang
chmod -R a+r extras
chmod -R a+r scripts
#create temp directory for debug log
mkdir temp
chmod a+rwx temp
currentDir=$(pwd)
#Check that zoom can run and is not missing any libraries
count=0
for i in $( ldd ZoomEngine | grep "not found" |cut -f2 | cut -d' ' -f1); do
	echo "Missing library: " $i
	count=1
done
echo
if [ $count -gt 0 ]; then
echo "Missing libraries detected!"
echo "To use Zoom you will need to install the above"
echo "libraries using your preferred package manager."
fi