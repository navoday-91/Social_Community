import boto3
import time
import pymysql.cursors
import sys
from pymysql import MySQLError

key_id = ""
accesskey = ""

community_name = ' '.join(sys.argv[1:])


elbv2 = boto3.client('elbv2', aws_access_key_id=key_id,
                         aws_secret_access_key=accesskey, region_name='us-west-1')


tgresponse = elbv2.create_target_group(
    Name=community_name,
    Port=80,
    Protocol='HTTP',
    VpcId='vpc-58eeb33c',
)

target_group_arn = tgresponse['TargetGroups'][0]['TargetGroupArn']

lbresponse = elbv2.create_load_balancer(
    Name=community_name,
    Subnets = ['subnet-a66966fe', 'subnet-f8f9079f'],
    SecurityGroups = ['sg-e22a2784']
)
lb_arn = lbresponse['LoadBalancers'][0]['LoadBalancerArn']
lb_ip = lbresponse['LoadBalancers'][0]['DNSName']
print(elbv2.describe_load_balancers(
    LoadBalancerArns=[lb_arn]
))

listenerresponse = elbv2.create_listener(
    DefaultActions=[
        {
            'TargetGroupArn': target_group_arn,
            'Type': 'forward',
        },
    ],
    Port=80,
    Protocol='HTTP',
    LoadBalancerArn=lb_arn,
)

auto_scaling = boto3.client('autoscaling', aws_access_key_id=key_id,
                         aws_secret_access_key=accesskey, region_name='us-west-1')
auto_scale_response = auto_scaling.create_auto_scaling_group(
    AutoScalingGroupName=community_name,
    LaunchConfigurationName='Community_Launch',
    MaxSize=3,
    MinSize=1,
    VPCZoneIdentifier='subnet-a66966fe, subnet-f8f9079f',
    TargetGroupARNs=[target_group_arn]
)

auto_scale_instance = auto_scaling.describe_auto_scaling_groups(
    AutoScalingGroupNames=[community_name]
)


rds = boto3.client('rds', aws_access_key_id=key_id,
                         aws_secret_access_key=accesskey, region_name='us-west-1')

rds_response = rds.create_db_instance(
    AllocatedStorage=5,
    DBName=''.join(e for e in community_name if e.isalnum()),
    DBInstanceClass='db.t2.micro',
    DBInstanceIdentifier=community_name+"rdsdb",
    Engine='MySQL',
    MasterUserPassword='redhat123',
    MasterUsername='admin',
    VpcSecurityGroupIds=['sg-e22a2784'],
    DBSubnetGroupName='community_db'
    )

rds_name = rds_response['DBInstance']['DBName']
rds_arn = rds_response['DBInstance']['DBInstanceArn']

rds_response = rds.describe_db_instances(
    DBInstanceIdentifier=community_name+'rdsdb',
)
while rds_response['DBInstances'][0]['DBInstanceStatus'] != 'available':
    time.sleep(60)
    rds_response = rds.describe_db_instances(
        DBInstanceIdentifier=community_name + 'rdsdb',
    )
rds_ip = rds_response['DBInstances'][0]['Endpoint']['Address']



try:
    db = pymysql.connect("54.183.103.17", "root", "redhat", "cmpe281")
except MySQLError as e:
    print(e)
# prepare a cursor object using cursor() method
cursor = db.cursor()
sql = "insert into community_details values('"+community_name+"',"+rds_arn+"',"+community_name+"rdsdb"+"',"+\
      rds_ip+"',"+target_group_arn+"',"+lb_arn+"',"+lb_ip+")"

try:
    cursor.execute(sql)
except:
    print("Error: unable to insert data")

