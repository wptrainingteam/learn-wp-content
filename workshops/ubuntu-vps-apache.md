# Apache

## Notes and resources

- https://www.digitalocean.com/community/tutorials/initial-server-setup-with-ubuntu
- https://www.digitalocean.com/community/tutorials/how-to-install-lamp-stack-on-ubuntu
- https://www.digitalocean.com/community/tutorials/how-to-install-linux-openlitespeed-mariadb-php-lomp-stack-on-ubuntu-22-04
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

```
/Users/jonathanbossenger/.ssh/id_psykrotek
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

```
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