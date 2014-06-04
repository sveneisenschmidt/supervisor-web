supervisor-web
===============

Simple web frontend to remote control a Supervisor instance.

![Index](/doc/index.png?raw=true "Index")


**Auto "tail" of logs**

![Log file viewer](/doc/process_log.gif?raw=true "Log file viewer")


### Installation

Clone the archive, copy the default configuration and adjust it for your needs. Fire up your browser and browse to the *public* folder inside the folder where you put the source code.
```bash

git clone https://github.com/sveneisenschmidt/supervisor-web.git
cd ./supervisor-web
cp config.json.dist config.json
```

```json
{
    "supervisor.host": "supervisor-host.intern",
    "supervisor.port": 9001
}
```
