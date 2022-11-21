#!/bin/sh
SHELLS=`cat /etc/shells | grep infosphere_hand`

mkdir /etc/infosphere_hand/
cp -r src/* /etc/infosphere_hand/
cp infosphere_hand.php /usr/local/bin/infosphere_hand
if [ "$SHELLS" = "" ]; then
    echo /usr/local/bin/infosphere_hand >> /etc/shells
fi
#chsh -s /usr/local/bin/infosphere_hand infosphere_hand
