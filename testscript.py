#!/usr/bin/python -u

import sys, time
# Turn on debug mode.
import cgitb
cgitb.enable()
# Print necessary headers.
print("Content-Type: text/html")
print()
print("Community requested is : {}\n".format(' '.join(sys.argv)))
time.sleep(1)
print("Community requested is : {}".format(' '.join(sys.argv)))
def page():
    yield (
        '<html><body><div id="counter">-</div>'
        '<script type="text/javascript">'
        '    function update(n) {'
        '        document.write(n);'
        '    }'
        '</script>'
    )
    for i in range(10):
        yield '<script type="text/javascript">update(%i);</script>'%i
        time.sleep(1)
    yield '</body></html>'
page()