import pymysql.cursors

from autobahn.twisted.websocket import WebSocketServerProtocol, \
    WebSocketServerFactory
from twisted.internet import reactor
from pymysql import MySQLError, ProgrammingError


class MyServerProtocol(WebSocketServerProtocol):
    liveclients = {}
    group_status = {}
    i = 0
    user = ""
    db = pymysql.connect("localhost", "admin", "redhat", "cmpe281")

    # prepare a cursor object using cursor() method
    cursor = db.cursor()

    def register(self, client, payload):
        """
        Add client to list of managed connections.
        """
        if self.i == 0:
            self.user = payload.decode('utf-8').split(";")[0]
            self.liveclients[self.user] = client
            self.i += 1

    def unregister(self, client):
        """
        Remove client from list of managed connections.
        """
        del self.liveclients[self.user]

    group_community = ""

    def isGroup(self, sendto):
        # prepare a cursor object using cursor() method
        global groupflag
        groupflag = False
        sql = "SELECT community, grouptype FROM groups where groupname = '" + sendto +"';"
        try:
            # Execute the SQL command
            self.cursor.execute(sql)
            # Fetch all the rows in a list of lists.
            results = self.cursor.fetchall()
            for row in results:
                groupflag = True
                global group_community
                group_community = row[0]
                return groupflag, row[1]
        except:
            print("Error: unable to fetch data")
        return False, None

    def botcommunicate(self, client, payload, isBinary):
        sendto = payload.decode('utf-8').split(";")[1]
        recdfrom = payload.decode('utf-8').split(";")[0]
        global group_community
        tablename = sendto + group_community + "rules"
        sql = "SELECT rule_txt, rule_val FROM `" + tablename +"`;"
        try:
            # Execute the SQL command
            self.cursor.execute(sql)
            # Fetch all the rows in a list of lists.
            results = self.cursor.fetchall()
            count = ""
            for row in results:
                count = row[0]
        except pymysql.Error as e:
            print("Error: unable to fetch data" + e)

    def communicate(self, client, payload, isBinary):
        sendto = payload.decode('utf-8').split(";")[1]
        group_flag, group_type = self.isGroup(sendto)
        if group_flag:
            if group_type == 'Bot':
                self.botcommunicate(self, payload, isBinary)
            else:
                self.groupcommunicate(self, payload, isBinary)
        else:
            if sendto not in self.liveclients:
                self.sendMessage((sendto + ";" + payload.decode('utf-8').split(";")[0] + ";" +
                                 "Sorry you don't have partner yet, check back in a minute").encode('ascii'))
            else:
                c = self.liveclients[sendto]
                c.sendMessage(payload, isBinary)

    def groupcommunicate(self, client, payload, isBinary):
        sendto = payload.decode('utf-8').split(";")[1]
        recdfrom = payload.decode('utf-8').split(";")[0]
        global group_community
        tablename = sendto + group_community
        sql = "SELECT * FROM `" + tablename + "`;"
        try:
            # Execute the SQL command
            self.cursor.execute(sql)
            # Fetch all the rows in a list of lists.
            results = self.cursor.fetchall()
            for row in results:
                if row[1] != "admin":
                    reciever = row[0]
                    if reciever != recdfrom and reciever in self.liveclients:
                        c = self.liveclients[reciever]
                        print(payload)
                        c.sendMessage(payload, isBinary)
        except pymysql.Error as e:
            print("Error: unable to fetch data" + e)

    def onConnect(self, request):
        print("Client connecting: {0}".format(request.peer))
        self.i = 0
        #self.register(self)
        #self.findPartner(self)

    def onOpen(self):
        print("WebSocket connection open.")

    def onMessage(self, payload, isBinary):
        if self.i == 0:
            self.register(self, payload)
        else:
            self.communicate(self,payload,isBinary)
        #self.sendMessage(payload, isBinary)

    def onClose(self, wasClean, code, reason):
        print("WebSocket connection closed: {0}".format(reason))
        self.unregister(self)


if __name__ == '__main__':

    import sys

    from twisted.python import log
    from twisted.internet import reactor

    log.startLogging(sys.stdout)
    factory = WebSocketServerFactory(u"ws://127.0.0.1:9000")
    factory.protocol = MyServerProtocol
    # factory.setProtocolOptions(maxConnections=2)

    # note to self: if using putChild, the child must be bytes...

    reactor.callInThread(reactor.listenTCP, 9000, factory)
    #reactor.listenTCP(9000, factory)
    reactor.run()