## Requirements

- PHP 7.1 or more

## Installation

## Homestead

* follow [documentation](https://laravel.com/docs/5.4/homestead) to install it

* edit your `Homestead.yaml` file - this will be located in your Homestead installation folder

```
ip: "192.168.10.10"
memory: 512
provider: virtualbox
authorize: ~/.ssh/id_rsa.pub
keys:
    - ~/.ssh/id_rsa
folders:
    - map: <path to your websites source code>
      to: /home/vagrant/Code
sites:
    - map: <your website1 local url>
      to: <vagrant path to your website1 public access>
    - map: <your website2 local url>
      to: <vagrant path to your website2 public access>
    ...
```

* edit your `/etc/hosts` file

```
192.168.10.10   <your website1 local url>
192.168.10.10   <your website2 local url>
```

* launch your virtual machine

```
vagrant provision
vagrant up
```
## Set up your environment
You will need to create a local .env file within your site root. To do this:
* Copy the existing .env file for staging server, should be called .env-staging
* Create a new file called .env and paste in the code
* Look for and change the following lines as detailed:

DB_PORT=33060
DB_DATABASE=sports
DB_USERNAME=homestead
DB_PASSWORD=secret

## API documentation

An API documentation is available and accessible by `http://<project vhost>/docs/index.html`.

This API documentation can be updated thanks to the following bash script: `./bash_scripts/update_api_docs.sh`.
