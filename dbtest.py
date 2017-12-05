import pymysql
from pymysql import MySQLError
rds_ip = "testcommrdsdb.cc5mcrpreogy.us-west-1.rds.amazonaws.com"
try:
    db = pymysql.connect(rds_ip, "admin", "redhat123")
except:
    print('failed1')
# prepare a cursor object using cursor() method
cursor = db.cursor()

sql = "create database cmpe281;"

try:
    cursor.execute(sql)
except:
    print("Error Here1")

db.commit()

try:
    db = pymysql.connect(rds_ip, "admin", "redhat123", "cmpe281")
except:
    print('failed')

sql = "create table login(`username` varchar(30), `password` varchar(20), `community_name` varchar(20));"
print(sql)

try:
    cursor.execute(sql)
except:
    print("Error Here2")

sql = "create table userdata(`username` varchar(30), `first name` varchar(20), `last name` varchar(20), `email` " \
      "varchar(50), `address` varchar(50), `phone` varchar(20), `community` varchar(40), `picurl` varchar(300));"
print(sql)

try:
    cursor.execute(sql)
except:
    print("Error Here3")

sql = "create table groups(`groupname` varchar(30), `grouptype` varchar(5), `community` varchar(40));"
print(sql)

try:
    cursor.execute(sql)
except:
    print("Error Here3")
db.commit()

db.close()