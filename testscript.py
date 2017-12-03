#!/usr/bin/python -u

import sys, time
# Turn on debug mode.
import cgitb
cgitb.enable()
# Print necessary headers.
print("Content-Type: text/html")
print()
print("Community requested is : {}\n".format(' '.join(sys.argv)))
sys.stdout.flush()
time.sleep(5)
print("Community requested is : {}".format(' '.join(sys.argv)))
sys.stdout.flush()