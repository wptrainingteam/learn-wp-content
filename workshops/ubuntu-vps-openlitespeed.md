# OpenLiteSpeed

## Notes and resources

- https://jonathanbossenger.com/how-to-install-linux-openlitespeed-mysql-php-stack-on-ubuntu/
- https://www.digitalocean.com/community/tutorials/how-to-install-linux-openlitespeed-mariadb-php-lomp-stack-on-ubuntu-22-04
- https://www.digitalocean.com/community/tutorials/initial-server-setup-with-ubuntu
- https://www.digitalocean.com/community/tutorials/how-to-install-lamp-stack-on-ubuntu
- https://docs.openlitespeed.org/config/
- https://upcloud.com/resources/tutorials/install-wordpress-openlitespeed
- https://developer.wordpress.org/advanced-administration/multisite/create-network/

## Initial server setup

Update software

```bash
apt update
apt upgrade -y
```

Set a hostname

```bash
hostnamectl set-hostname psykrotek
```

Create a new user

```bash
adduser jbossenger
usermod -aG sudo jbossenger
```

Set up a basic firewall

```bash
sudo ufw allow OpenSSH
sudo ufw enable
sudo ufw status
```

Optional, configure ssh key pair

```bash
ssh-keygen -t ed25519 -C "jonathanbossenger@Jonathans-MBP"
```

Copy the public key

```bash
cat ~/.ssh/id_ed25519.pub
```

Create the .ssh directory on the server, and the authorized_keys file

```bash
mkdir   ~/.ssh
chmod 700 ~/.ssh
nano ~/.ssh/authorized_keys
```

Paste the public key into the authorized_keys file, and update the permissions

```bash
chmod 600 ~/.ssh/authorized_keys
```

Disable password authentication

```bash
sudo nano /etc/ssh/sshd_config
```

Set the following values

```bash
PermitRootLogin no
PasswordAuthentication no
```

Check additional ssh configuration

```bash
cd /etc/ssh/sshd_config.d
```

Restart the ssh service

```bash 
sudo service ssh restart
```

## Install OpenLiteSpeed, MySQL, and PHP

```bash
sudo wget -O - https://repo.litespeed.sh | sudo bash
sudo apt update
sudo apt install openlitespeed
```

Check that the service is running

```bash
sudo systemctl status lsws
```

Update the firewall to allow access to the web server

```bash
sudo ufw allow 7080,80,443,8088/tcp
sudo ufw status
```

Access the web server

```bash
http://your_server_ip:8088
```

Install MySQL

```bash
sudo apt install mysql-server
```

Update MySQL root password

```bash
sudo mysql
```

```sql
ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY 'password';
FLUSH PRIVILEGES;
exit
```

Secure the MySQL installation

```bash
sudo mysql_secure_installation
```

```bash
Would you like to setup VALIDATE PASSWORD component? Y
Please enter 0 = LOW, 1 = MEDIUM and 2 = STRONG: 2
Change the password for root ? Y
New password: ************
Remove anonymous users? Y
Disallow root login remotely? Y
Remove test database and access to it? Y
Reload privilege tables now? Y
```

Update PHP version PHP 8.1 on Ubuntu 22.04 by default

```bash
sudo apt install lsphp81 lsphp81-{common,mysql}
```

Install PHP CLI

```bash
sudo apt install php8.1-cli
```

Configure OpenLiteSpeed admin

```bash
sudo /usr/local/lsws/admin/misc/admpass.sh
```

Browse to the admin interface

```
https://your_server_ip:7080
```

https://docs.openlitespeed.org/config/php/#configuration

Server Configuration > External App > Edit

Change Command to lsphp81/bin/lsphp

Graceful restart

## Set up a virtual host

https://docs.openlitespeed.org/config/#set-up-virtual-hosts

Create the directories

```bash
sudo mkdir /usr/local/lsws/psykrotek
sudo mkdir /usr/local/lsws/psykrotek/{conf,html,logs}

sudo chown -R nobody:nogroup /usr/local/lsws/psykrotek/html
sudo find /usr/local/lsws/psykrotek/html/ -type d -exec chmod 750 {} \;
sudo find /usr/local/lsws/psykrotek/html/ -type f -exec chmod 640 {} \;

sudo chown lsadm:lsadm /usr/local/lsws/psykrotek/conf
```

Configure the vhost in the admin interface

Virtual Hosts > Add

Virtual Host Name = psykrotek
Virtual Host Root = $SERVER_ROOT/psykrotek
Config File = $SERVER_ROOT/conf/vhosts/psykrotek/vhost.conf
Enable Scripts/ExtApps = Yes
Restrained = No

file /usr/local/lsws/conf/vhosts/psykrotek/vhost.conf does not exist. CLICK TO CREATE

Virtual Hosts > psykrotek > General

Document Root = /usr/local/lsws/psykrotek/html
Domain Name = psykrotek.co.za
Domain Aliases = www.psykrotek.co.za, *.psykrotek.co.za

Virtual Hosts > psykrotek > Index Files

index.html, index.php

Enable Rewrite in the Rewrite tab

Enable Rewrite = Yes
Auto Load from .htaccess = yes

Listeners -> Add

Listener Name = HTTP
IP Address = ANY IPv4
Port = 80
Secure = No

Map Virtual Hosts

Listeners > HTTP > Virtual Host Mappings > Add

Virtual Host = psykrotek
Domains = psykrotek.co.za, www.psykrotek.co.za, *.psykrotek.co.za

Graceful restart

## Install WordPress

Instal WP-CLI

```bash
curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
chmod +x wp-cli.phar
sudo mv wp-cli.phar /usr/local/bin/wp
```

Create the database

```bash
mysql -uroot -p
```

```sql
CREATE DATABASE psykrotek;
CREATE USER 'psykrotek'@'localhost' IDENTIFIED BY 'psykrotekdbpassword';
GRANT ALL PRIVILEGES ON psykrotek.* TO 'psykrotek'@'localhost';
FLUSH PRIVILEGES;
exit
```

Download WordPress

```bash
sudo su 
cd /usr/local/lsws/psykrotek/html
wp core download --allow-root
```

