import sys, time
# Turn on debug mode.
print("Community requested is : {}\n".format(' '.join(sys.argv[1:])))
time.sleep(1)
print("Community requested is : {}".format(' '.join(sys.argv[1:])))
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