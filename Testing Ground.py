import paramiko
import boto3
import time

def createinstance():
    ec2 = boto3.resource('ec2', aws_access_key_id="",
                         aws_secret_access_key="", region_name='us-west-1')
    for instance in ec2.instances.all():
        print(instance.id, instance.state)

    instance = ec2.create_instances(
        ImageId='ami-45ead225',
        MinCount=1,
        MaxCount=1,
        InstanceType='t2.micro',
        KeyName = 'masternode281',
        SecurityGroupIds = ['sg-e22a2784']
    )
    print("Your New Instance : ", instance[0].id, instance[0].public_ip_address, instance[0].state['Name'])
    print("Waiting to complete....")
    instance[0].wait_until_running()
    time.sleep(30)
    print("Now Provisioning....")
    build_depencies(instance[0].public_ip_address)
    return(instance[0].id, instance[0].public_ip_address, instance[0].state['Name'])

def build_depencies(host_ip):
    replace_CGI1 = "sudo sed -i.bak '2i\    <Directory /var/www/html>' /etc/apache2/sites-enabled/000-default.conf"
    replace_CGI2 = "sudo sed -i.bak '3i\        Options +ExecCGI' /etc/apache2/sites-enabled/000-default.conf"
    replace_CGI3 = "sudo sed -i.bak '4i\        DirectoryIndex index.py' /etc/apache2/sites-enabled/000-default.conf"
    replace_CGI4 = "sudo sed -i.bak '5i\    </Directory>' /etc/apache2/sites-enabled/000-default.conf"
    replace_CGI5 = "sudo sed -i.bak '2i\    AddHandler cgi-script .py' /etc/apache2/sites-enabled/000-default.conf"

    k = paramiko.RSAKey.from_private_key_file("/Users/navodaytomar/Downloads/masternode281.pem")
    c = paramiko.SSHClient()
    c.set_missing_host_key_policy(paramiko.AutoAddPolicy())
    print("connecting......!")
    c.connect(hostname=host_ip, username="ubuntu", pkey=k)
    commands = ["sudo apt-get -y update && sudo apt-get -y upgrade",
                replace_CGI1,
                replace_CGI2,
                replace_CGI3,
                replace_CGI4,
                replace_CGI5,
                "echo \"America/Chicago\" > sudo /etc/timezone",
                "sudo dpkg-reconfigure -f noninteractive tzdata",
                "sudo apt-get -y install zsh htop",
                "echo \"mysql-server-5.6 mysql-server/root_password password redhat\" | sudo debconf-set-selections",
                "echo \"mysql-server-5.6 mysql-server/root_password_again password redhat\" | sudo debconf-set-selection"
                "s",
                "sudo apt-get -y install mysql-server",
                "echo \"root\n\nY\nredhat\nredhat\n\n\nn\n\n \" | mysql_secure_installation 2>/dev/null",
                "sudo apt-get -y install apache2",
                "sudo apt-get -y install php7.0 libapache2-mod-php7.0",
                "sudo systemctl restart apache2",
                "sudo apt-cache search php7.0",
                "sudo apt-get -y install php7.0-mysql php7.0-curl",
                "sudo systemctl restart apache2",
                "sudo apt-get -y install php7.0-opcache php-apcu",
                "sudo apt-get -y install python3",
                "sudo apt-get -y install python3-pip",
                "sudo rm /var/www/html/index.html",
                "cd /var/www/html && sudo git init && sudo git pull https://github.com/navoday-91/Social_Community.git && c"
                "d",
                "sudo rm /usr/bin/python",
                "sudo ln -s /usr/bin/python3 /usr/bin/python",
                "sudo -H pip3 install pymysql",
                "sudo a2dismod mpm_event",
                "sudo a2enmod mpm_prefork cgi",
                "sudo a2enmod cgi",
                "sudo systemctl restart apache2",
                "sudo -H pip3 install awscli boto3 -U --ignore-installed six",
                "sudo -H pip3 install pymysql",
                "sudo -H pip3 install paramiko",
                "sudo -H pip3 install autobahn[twisted]",
                ]
    for command in commands:
        print("Executing {}".format(command))
        stdin, stdout, stderr = c.exec_command(command)
        print(stdout.read())
        print("Errors")
        print(stderr.read())
    c.close()

ec2 = boto3.resource('ec2', aws_access_key_id="",
                         aws_secret_access_key="", region_name='us-west-1')
for instance in ec2.instances.all():
    print(instance.id, instance.state['Name'], instance.public_ip_address)

print("Building a new machine now....")
build_depencies('54.241.130.51')
print("Details of your instance - ", createinstance())
