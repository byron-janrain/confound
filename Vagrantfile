Vagrant.configure("2") do |config|

  config.vm.box = "precise64"
  config.vm.network :private_network

  config.vm.synced_folder "salt/roots/", "/srv/salt/"
  config.vm.provision :salt do |salt|
    salt.minion_config = "salt/minion.conf"
    salt.run_highstate = true
  end

  config.vm.define :web do |web|
    web.vm.hostname = 'web'
  end

  config.vm.define :mysql do |mysql|
    mysql.vm.hostname = 'mysql'
  end
end
