#!/bin/sh
SHELLS=`cat /etc/shells | grep infosphere_hand`

cp infosphere_hand /usr/local/bin/
if [ "$SHELLS" = "" ]; then
    echo /usr/local/bin/infosphere_hand >> /etc/shells
fi
chsh -s /usr/local/bin/infosphere_hand infosphere_hand
