# Pizza Service 
Der Pizza-Service ist eine webbasierte Anwendung. Ziel ist es, eine vollständig integrierte Plattform zu schaffen, die es Nutzern ermöglicht, Pizzen zu bestellen, personalisierte Optionen auszuwählen und die Bestellung nahtlos abzuwickeln.

![image info](src/Praktikum/readme-image/Uebersicht-seite.png)
![image info](src/Praktikum/readme-image/Bestellung-seite.png)
![image info](src/Praktikum/readme-image/Baecker-seite.png)
![image info](src/Praktikum/readme-image/Fahrer-seite.png)
![image info](src/Praktikum/readme-image/Kunde-seite.png)

## Table of Contents
1. [Technologies](#technologies)
2. [Prerequisites](#prerequisites)
3. [Running the Application](#running-the-application)
4. [Stop the Application](#stop-the-application)

## Technologies
- **HTML5**: Semantische und standardkonforme Strukturierung der Webseiten.
- **CSS3**: Gestaltung und responsives Design für alle Endgeräte.
- **JavaScript**: Dynamische Nutzerinteraktion und Inhaltsaktualisierung.
- **AJAX**: Asynchrone Serverkommunikation ohne Seitenreload.

## Prerequisites

To run this project, you need to have Docker installed on your system

Install the `docker` tools as explained here: https://docs.docker.com/engine/install/

For Linux you may have to install `docker-compose` separately. Please check the manuals.

## Running the Application

### Initial Setup

In the folder, where the `docker-compose.yml` file is located, edit the file called `env.txt` in order to assign a root password for your database as environment variable. In order to avoid pushing your password to the repo you may add `env.txt` to the local `.gitignore`.
Hint: The docker setup uses the ports 80, 3306 and 8085. Please make sure that these ports are not in use by any other software when starting the containers. 

### Start the Containers

Start your local docker containers in a console window with `docker-compose up -d`. 
After a while (and a lot of messages) you should have 3 containers running:
- php-apache: Containing Apache Webserver and PHP
- MariaDB: your database server for SQL
- PHPmyAdmin a web-based application to modify your database 

All files in the `src`-folder are linked into the apache-php container, so you can see your changes while developing in that folder. Everything is set up and deployed automatically.
Note the folder `src\Log` containing the log files of the docker containers (e.g. apache log)

### Test the Installation

Go to [http://localhost](http://localhost) to check the served code. After installation you will see the content of the file `index.php` from the src-folder. 

You can select a file by specifying a path starting from the src-folder to the file at the end of the URL (please be aware that the containers run on linux and linux is case sensitive).

### Head to Pizza Service Project
This project demonstrates a pizza ordering service as part of a practical web application. To access the application, navigate to the following URL in your browser: 
[http://localhost/Praktikum/Prak5/Uebersicht.php](http://localhost/Praktikum/Prak5/Uebersicht.php)  

## Stop the Application
Call `docker-compose down` to stop the containers.



