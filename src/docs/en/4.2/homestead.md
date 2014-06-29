# Laravel Homestead

- [Introduction](#introduction)
- [Included Software](#included-software)
- [Installation & Setup](#installation-and-setup)
- [Daily Usage](#daily-usage)
- [Ports](#ports)

<a name="introduction"></a>
## Introduction

Laravel strives to make the entire PHP development experience delightful, including your local development environment. [Vagrant](http://vagrantup.com) provides a simple, elegant way to manage and provision Virtual Machines.

Laravel Homestead is an official, pre-packaged Vagrant "box" that provides you a wonderful development environment without requiring you to install PHP, a web server, and any other server software on your local machine. No more worrying about messing up your operating system! Vagrant boxes are completely disposable. If something goes wrong, you can destroy and re-create the box in minutes!

Homestead runs on any Windows, Mac, and Linux, and includes the Nginx web server, PHP 5.5, MySQL, Postgres, Redis, Memcached and all of the other goodies you need to develop amazing Laravel applications.

Homestead is currently built and tested using Vagrant 1.6.

<a name="included-software"></a>
## Included Software

- Ubuntu 14.04
- PHP 5.5
- Nginx
- MySQL
- Postgres
- Node (With Bower, Grunt, and Gulp)
- Redis
- Memcached
- Beanstalkd
- [Laravel Envoy](/docs/ssh#envoy-task-runner)
- Fabric + HipChat Extension

<a name="installation-and-setup"></a>
## Installation & Setup

### Installing VirtualBox & Vagrant

Before launching your Homestead environment, you must install [VirtualBox](https://www.virtualbox.org/wiki/Downloads) and [Vagrant](http://www.vagrantup.com/downloads.html). Both of these software packages provide easy-to-use visual installers for all popular operating systems.

### Adding The Vagrant Box

Once VirtualBox and Vagrant have been installed, you should add the `laravel/homestead` box to your Vagrant installation using the following command in your terminal. It will take a few minutes to download the box, depending on your Internet connection speed:

	vagrant box add laravel/homestead

### Clone The Homestead Repository

Once the box has been added to your Vagrant installation, you should clone or download this repository. Consider cloning the repository into a central `Homestead` directory where you keep all of your Laravel projects, as the Homestead box will serve as the host to all of your Laravel (and PHP) projects.

	git clone https://github.com/laravel/homestead.git Homestead

### Set Your SSH Key

Next, you should edit the `Homestead.yaml` file included in the repository. In this file, you can configure the path to your public SSH key, as well as the folders you wish to be shared between your main machine and the Homestead virtual machine.

Don't have an SSH key? On Mac and Linux, you can generally create an SSH key pair using the following command:

	ssh-keygen -t rsa -C "your@email.com"

On Windows, you may install [Git](http://git-scm.com/) and use the `Git Bash` shell included with Git to issue the command above. Alternatively, you may use [PuTTY](http://www.chiark.greenend.org.uk/~sgtatham/putty/download.html) and [PuTTYgen](http://www.chiark.greenend.org.uk/~sgtatham/putty/download.html).

Once you have created a SSH key, specify the key's path in the `authorize` property of your `Homestead.yaml` file.

### Configure Your Shared Folders

The `folders` property of the `Homestead.yaml` file lists all of the folders you wish to share with your Homestead environment. As files within these folders are changed, they will be kept in sync between your local machine and the Homestead environment. You may configure as many shared folders as necessary!

### Configure Your Nginx Sites

Not familiar with Nginx? No problem. The `sites` property allows you to easily map a "domain" to a folder on your Homestead environment. A sample site configuration is included in the `Homestead.yaml` file. Again, you may add as many sites to your Homestead environment as necessary. Homestead can serve as a convenient, virtualized environment for every Laravel project you are working on!

### Bash Aliases

To add Bash aliases to your Homestead box, simply add to the `aliases` file in the root of the Homestead directory.

### Launch The Vagrant Box

Once you have edited the `Homestead.yaml` to your liking, run the `vagrant up` command from the Homestead directory in your terminal. Vagrant will boot the virtual machine, and configure your shared folders and Nginx sites automatically!

Don't forget to add the "domains" for your Nginx sites to the `hosts` file on your machine! The `hosts` file will redirect your requests for the local domains into your Homestead environment. On Mac and Linux, this file is located at `/etc/hosts`. On Windows, it is located at `C:\Windows\System32\drivers\etc\hosts`. The lines you add to this file will look like the following:

	127.0.0.1  homestead.app

Once you have added the domain to your `hosts` file, you can access the site via your web browser on port 8000!

	http://homestead.app:8000

To learn how to connect to your databases, read on!

<a name="daily-usage"></a>
## Daily Usage

### Connecting Via SSH

To connect to your Homestead environment via SSH, you should connect to `127.0.0.1` on port 2222 using the SSH key you specified in your `Homestead.yaml` file. You may also simply run the `vagrant ssh` command from your `Homestead` directory.

If you want even more convenience, it can be helpful to add the following alias to your `~/.bash_aliases` or `~/.bash_profile`:

	alias vm='ssh vagrant@127.0.0.1 -p 2222'

### Connecting To Your Databases

A `homestead` database is configured for both MySQL and Postgres out of the box. For even more convenience, Laravel's `local` database configuration is set to use this database by default.

To connect to your MySQL or Postgres database from your main machine via Navicat or Sequel Pro, you should connect to `127.0.0.1` and port 33060 (MySQL) or 54320 (Postgres). The username and password for both databases is `homestead` / `secret`.

> **Note:** You should only use these non-standard ports when connecting to the databases from your main machine. You will use the default 3306 and 5432 ports in your Laravel database configuration file since Laravel is running _within_ the Virtual Machine.

### Adding Additional Sites

Once your Homestead environment is provisioned and running, you may want to add additional Nginx sites for your Laravel applications. You can run as many Laravel installations as you wish on a single Homestead environment. There are two ways to do this: First, you may simply add the sites to your `Homestead.yaml` file and then run `vagrant provision`.

Alternatively, you may use the `serve` script that is available on your Homestead environment. To use the `serve` script, SSH into your Homestead environment and run the following command:

	serve domain.app /home/vagrant/Code/path/to/public/directory

> **Note:** After running the `serve` command, do not forget to add the new site to the `hosts` file on your main machine!

<a name="ports"></a>
## Ports

The following ports are forwarded to your Homestead environment:

- **SSH:** 2222 -> Forwards To 22
- **HTTP:** 8000 -> Forwards To 80
- **MySQL:** 33060 -> Forwards To 3306
- **Postgres:** 54320 -> Forwards To 5432
