#!/usr/bin/python

import sys
# Turn on debug mode.
import cgitb
cgitb.enable()
# Print necessary headers.
print("Content-Type: text/html")
print()
comm = sys.argv[1]
print("Community requested is : {}".format(comm))