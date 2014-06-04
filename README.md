supervisor-web
===============

Simple web frontend to remote control a Supervisor instance.

![Index](/doc/index.png?raw=true "Index")


**Auto "tail" of logs**

![Log file viewer](/doc/process_log.gif?raw=true "Log file viewer")


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
    "supervisor.port": 9001
}
```

Get composer and install all dependencies.
```bash
curl -sS https://getcomposer.org/installer | php
php composer.phar install
```

Symlink the *public* folder to your web server folder and open up the folder in your browser.

