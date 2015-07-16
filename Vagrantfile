Vagrant.configure(2) do |config|
  config.vm.box = "chef/centos-6.5"
  #config.vm.network "private_network", ip: "192.168.33.33"
  config.vm.hostname = "serkin"
  config.vm.synced_folder ".", "/vagrant"
  
  
  config.vm.provision "shell", inline: "yum install -y mc"

  config.vm.provision "shell", path: "vagrant/provision/php.sh"
  config.vm.provision "shell", path: "vagrant/provision/composer.sh"
  
end
