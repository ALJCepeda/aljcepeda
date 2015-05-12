# -*- mode: ruby -*-
# vi: set ft=ruby :

# All Vagrant configuration is done below. The "2" in Vagrant.configure
# configures the configuration version (we support older styles for
# backwards compatibility). Please don't change it unless you know what
# you're doing.
Vagrant.configure(2) do |config|
  # The most common configuration options are documented and commented below.
  # For a complete reference, please see the online documentation at
  # https://docs.vagrantup.com.

  # Every Vagrant development environment requires a box. You can search for
  # boxes at https://atlas.hashicorp.com/search.
  config.vm.box = "ubuntu/trusty64"

  config.vm.network :private_network, ip: "192.168.2.21"
  $final = <<SCRIPT
  	#Download personal settings
  	mkdir ~/bash
    sudo rm /var/www/html/index.html

  	wget "https://gist.githubusercontent.com/ALJCepeda/d90844bf63e23a06d3d3/raw/0e913197c7772a16381854712666dbb1eb38ce38/gistfile1.sh" -O ~/bash/git-prompt.sh >/dev/null 2>&1
  	wget "https://gist.githubusercontent.com/ALJCepeda/dc006ba37c7befec4f42/raw/3e5ef3b3bb2c1bb27088b1cfe5c7e4b012a83555/gistfile1.sh" -O ~/.bash_profile >/dev/null 2>&1
  	wget "https://gist.githubusercontent.com/ALJCepeda/20b55b2bbb7671764933/raw/fcfafabd4e7a56a856f8a35ab7f0640a13009787/gistfile1.txt" -O ~/.gitconfig >/dev/null 2>&1

    echo Final Server Configurations
    sudo a2enmod rewrite >/dev/null 2>&1
    printf '%s\n\t%s\n%s' '<Directory "/var/www/html">' 'AllowOverride All' '</Directory>' | sudo tee -a /etc/apache2/sites-available/000-default.conf >/dev/null 2>&1
    sudo service apache2 restart >/dev/null 2>&1

  	echo "Welcome to ALJCepeda\'s Dev Environment!"
  	echo "Technologies: LAMP, GIT, Composer, Bower and more"
SCRIPT

  config.vm.provision "shell", inline: "(grep -q -E '^mesg n$' /root/.profile && sed -i 's/^mesg n$/tty -s \\&\\& mesg n/g' /root/.profile && echo 'stdin: is not a tty; Error has been fixed') || exit 0;"
  config.vm.provision "shell", path: "https://gist.githubusercontent.com/ALJCepeda/b5ba70bfa367d04b95c7/raw/047b0ca12cb1d2ee12f90f5f9e191e8b5afd48b4/gistfile1.sh"
  config.vm.provision "file", source: "~/.ssh/id_rsa", destination: "/home/vagrant/.ssh/id_rsa"
  config.vm.provision "file", source: "~/.ssh/id_rsa.pub", destination: "/home/vagrant/.ssh/id_rsa.pub"
  config.vm.provision "shell", inline: $final, privileged: false

  config.vm.synced_folder "./", "/var/www/html"
  config.vm.synced_folder "~/vendor", "/home/vagrant/vendor"
end

#