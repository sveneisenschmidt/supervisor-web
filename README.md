supervisor-web
===============

Simple web frontend to remote control a Supervisor instance.

![Index](/doc/index.png?raw=true "Index")



### Installation

Clone the archive. 
```bash

git clone https://github.com/sveneisenschmidt/supervisor-web.git
cd ./supervisor-web
```

Copy the default configuration and adjust it for your needs.
```bash

cp config.json.dist config.json
```
```json
{
    "supervisor.host": "supervisor-host.intern",
    "supervisor.port": 9001,
    "supervisor.user": "username",
    "supervisor.pass": "password"
}
```

Get composer and install all dependencies.
```bash
curl -sS https://getcomposer.org/installer | php
php composer.phar install
```

Symlink the *public* folder to your web server folder and open up the folder in your browser.

### Purpose

Obviously you could use the stock Supervisor web gui of this web app.
The advantage of *supervisor-web* are:

* You can put it behind a proxy web server (add LDAP auth, ssl)
* You can put it on another machine
* You can fork it and change it to your needs
* You can *tail -f* your logs in any browser


**Auto "tail" of logs**

![Log file viewer](/doc/process_log.gif?raw=true "Log file viewer")

### Credits

* [indigophp/supervisor](//github.com/indigophp/supervisor)
* [silexphp/silex](//github.com/indigophp/supervisor)
* [Supervisor/supervisor](//github.com/Supervisor/supervisor)

