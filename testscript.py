#!/usr/bin/python

import sys
# Turn on debug mode.
import cgitb
cgitb.enable()
# Print necessary headers.
print("Content-Type: text/html")
print()
print("Community requested is : {}".format(' '.join(sys.argv[1])))
