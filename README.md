<p align='center'>
    <img src='https://github.com/JacksonK52/BtSystem/images/BtSystem.png' width=320px />
    <h2 align='center' style='text-align: center'>BtSystem</h2>
</p>

## Introduction

---

A bug tracking system or defect tracking system is a software application that keeps track of reported software bugs in software development projects.

## How to install

---

1. First of all install and setup xampp in your system.

2. Download the project by clicking on the link https://github.com/JacksonK52/BtSystem/archive/refs/heads/main.zip or clone the project using the command.

    > $ git clone https://github.com/JacksonK52/BtSystem.git

3. Setup your virtual host for the project with the serverName 'btsystem.io'.

4. Update or install the required packages of the project by running the command.

    > $ composer update

5. Create a database in your phpmyadmin with the name "btsystem_db" with the charset "utf8mb4_general_ci".

6. Migrate all the necessary tables using the command

    > $ php yii migrate

8. Create a php file with the name 'email.php' under 'config' folder, past the code below and filled the necessary information 
    ```php
    <?php
        // Email Setup
        return [
            'class' => 'Swift_SmtpTransport',
            'host' => '',
            'username' => '',
            'password' => '',
            'port' => '',
            'encryption' => '',
        ];
    ```

7. Now you are all set to run the project, open your browser and goto your server name that you have setup during your virtual host.