import boto3, datetime
from termcolor import colored

key_id = ""
accesskey = ""


def get_metrics_elb(asset):
    client1 = boto3.client('elbv2',aws_access_key_id=key_id,
                         aws_secret_access_key=accesskey, region_name='us-west-1')
    response1 = client1.describe_load_balancers(
        Names=[asset],

    )

    for instancestates in response1['LoadBalancers']:
        if instancestates['State']['Code'] == 'active':
            ins_state = colored(instancestates['State']['Code'],'green')
        else:
            ins_state = colored(instancestates['State']['Code'],'red')

        print('Load Balancer Name: ', instancestates['LoadBalancerName'], '| Instance State: ', ins_state)


def get_metrics_rds(asset):
    client1 = boto3.client('rds',aws_access_key_id=key_id,
                         aws_secret_access_key=accesskey, region_name='us-west-1')
    response1 = client1.describe_db_instances(
        DBInstanceIdentifier=(asset+"rdsdb").lower(),)
    print(colored("DB Instance",'green'))
    instancestates = response1['DBInstances'][0]
    if instancestates['DBInstanceStatus'] == 'available':
        ins_state = colored(instancestates['DBInstanceStatus'],'green')
    else:
        ins_state = colored(instancestates['DBInstanceStatus'],'red')
    print(instancestates['Engine'].upper() + ' RDS Instance Endpoint: ', instancestates['Endpoint']['Address'],\
              '| Instance State: ', ins_state)
    print('Allocated Storage: ', colored(str(instancestates['AllocatedStorage']) + " GB", 'yellow'),
          '| Instance Version: ', instancestates['EngineVersion'])


def get_metrics_ec2(asset):
    client = boto3.client('cloudwatch',aws_access_key_id=key_id,
                         aws_secret_access_key=accesskey, region_name='us-west-1')
    response = client.get_metric_statistics(
        Namespace='AWS/EC2',
        MetricName='CPUUtilization',
        StartTime=datetime.datetime.utcnow()-datetime.timedelta(seconds=7200),
        EndTime=datetime.datetime.utcnow(),
        Period=300,
        Dimensions=[
            {
                'Name': 'InstanceId',
                'Value': asset
            },
        ],
        Statistics=['Average'],
        Unit='Percent'
    )
    newlist=response['Datapoints']
    newlist = sorted(newlist, key=lambda k: k['Timestamp'])
    if len(newlist) > 0:
        return newlist[-1]['Average']


def asg():
    client = boto3.client('autoscaling',aws_access_key_id=key_id,
                         aws_secret_access_key=accesskey, region_name='us-west-1')
    response = client.describe_auto_scaling_groups()
    ASGs = response['AutoScalingGroups']
    print("\nYou Have " + str(len(ASGs)) + " Communities Configured!\n\n")
    healthy = 0
    unhealthy = 0
    for ASG in ASGs:
        print('#'*150)
        print(colored(ASG['AutoScalingGroupName'],'red'))
        print('ASG Min Size: ', ASG['MinSize'])
        print('ASG Max Size: ', ASG['MaxSize'])
        print('ASG Desired Size: ', ASG['DesiredCapacity'])
        print('ASG instance count: ', len(ASG['Instances']))
        print('#'*150)

        for instance in ASG['Instances']:
            if instance['HealthStatus'] == 'Healthy':
                ins_health = colored('Healthy', 'green')
                ins_cpu = get_metrics_ec2(instance['InstanceId'])
                if ins_cpu and ins_cpu > 5:
                    ins_cpu = colored(ins_cpu, 'red')
                else:
                    ins_cpu = colored(ins_cpu, 'green')
                healthy += 1
            else:
                ins_health = colored(instance['HealthStatus'], 'red')
                unhealthy += 1
            if instance['LifecycleState'] == 'InService':
                ins_life = colored(instance['LifecycleState'], 'green')
            else:
                ins_life = colored(instance['LifecycleState'], 'red')

            print('Instance Id: ', colored(instance['InstanceId'],'yellow'), '| Instance Zone:', colored(instance['AvailabilityZone'],'yellow'), ' | Instance LifecycleState: ', ins_life, '| Instance Status: ', ins_health, '| Instance Cpu: ', ins_cpu)

        print('#'*150)
        print('ASG Healthy Instance Count: ', colored(healthy, "green"))
        print('ASG Unhealthy Instance Count: ', colored(unhealthy, "red"))

        #for ELB in (ASG['LoadBalancerNames']):
        ELB = ASG['AutoScalingGroupName']
        print('#'*150)
        print('ELB Name: ', ELB)
        print('#'*150)
        get_metrics_elb(ELB)
        print('#' * 150)
        get_metrics_rds(ELB)
        print('#'*150)


if __name__ == "__main__":
    try:
        asg()
    except Exception as err:
        print(err)
