<h3>Symfony Todo API</h3> 
Hello my dear friend!<br>
I would like to invite you to consider my application!<br>
<h4>For the developer:</h4>
The app is written in php using symfony and bootstrap frameworks. To deploy my project on your local computer you have to do a composer install.<br>
<h4>For the user:</h4>
This app is designed for you to look at your task list every day and remember to do them! The app allows you to mark already completed tasks, create new ones and delete old ones. You can see the demo version here https://iuriit.allbugs.info/checklist

<h3>Answering the question: How do I run a cloned project on another computer?</h3> 
Generate a certificate for the site in ~/misc/certs/ folder (there is a command to generate a certificate on top of docker-compose.yaml file in the comments)
Add information about certificate to ~/misc/apps/docker_infrastructure/local_infrastructure/configuration/certificates.toml (3 lines, examples are already there)
Add domains to /etc/hosts
Run docker-compose up -d
Create auth.json in the project if needed.
Go into the container with Apache and run composer install
Roll back any changed files like .gitignore or pub/.htaccess
If it is a project with a database - create a database, user, give permissions, unwrap the database from the dump command MySQL
USE db_name
SOURCE path_to_sql_file
