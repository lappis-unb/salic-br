#!/bin/bash

#modifica os hosts
bash /changehosts.sh

#inicia o apache
apache2ctl -D FOREGROUND

exit 0
