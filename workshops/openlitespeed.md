# OpenLiteSpeed

## Notes and resources

https://www.digitalocean.com/community/tutorials/initial-server-setup-with-ubuntu

https://www.digitalocean.com/community/tutorials/how-to-install-lamp-stack-on-ubuntu

https://www.digitalocean.com/community/tutorials/how-to-install-linux-openlitespeed-mariadb-php-lomp-stack-on-ubuntu-22-04

https://docs.openlitespeed.org/config/

sudo apt install php8.3-cli for wp cli

https://upcloud.com/resources/tutorials/install-wordpress-openlitespeed

sudo chown -R nobody:nogroup /usr/local/lsws/Example/html/wordpress

sudo find /usr/local/lsws/Example/html/wordpress/ -type d -exec chmod 750 {} \;
sudo find /usr/local/lsws/Example/html/wordpress/ -type f -exec chmod 640 {} \;

## Initial server setup

Update software

```bash
sudo apt update
sudo apt upgrade -y
```

Set a hostname

```bash
sudo hostnamectl set-hostname psykrotek
```

Create a new user

```bash
sudo adduser psykrotek
sudo usermod -aG sudo psykrotek
```

Set up a basic firewall

```bash
sudo ufw allow OpenSSH
sudo ufw enable
ufw status
```

Optional, configure ssh key pair

```bash
ssh-keygen -t ed25519 -C "jonathanbossenger@Jonathans-MBP"
```

```
/Users/jonathanbossenger/.ssh/id_psykrotek
```

Copy the public key

```bash
cat ~/.ssh/id_psykrotek.pub
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

```
PermitRootLogin no
PasswordAuthentication no
```

Restart the ssh service

```bash 
sudo service ssh restart
```

Check additional ssh configuration

```bash
/etc/ssh/sshd_config.d
```

## Install OpenLiteSpeed

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

```
http://your_server_ip:8088
```

Update PHP version (if needed)

```bash
sudo apt-get install lsphp83 lsphp83-common lsphp83-mysql
```

sudo update-alternatives --set php /usr/bin/php8.3
sudo update-alternatives --set phar /usr/bin/phar8.3
sudo update-alternatives --set phar.phar /usr/bin/phar.phar8.3
sudo update-alternatives --set phpize /usr/bin/phpize8.3
sudo update-alternatives --set php-config /usr/bin/php-config8.3


Install PHP cli, match the version to the PHP version installed with OpenLiteSpeed

```bash
sudo apt install php7.3-cli
```





