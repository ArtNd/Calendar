<div align="center">
	<img src="public/Img/Logo.png" width="100">
	<h1 class="text-center"> 
		Calendar. 
	</h1>
	<span> Pour une organisation connectée</span>
</div>

<h2 align="center"> Projet web de <a>Jules Escudié et Arthur Nardone</a></h2>
	
  <h3 > INTRODUCTION </h3>
  Calendar est un site de réservation de salles qui peut être mis à disposition pour l’environnement informatique d’un école ou d’un établissement (par exemple l'ICAM). 

  Ce site permet à un élève ou tout autre personne autorisée de réserver l’accès à une salle de classe, une salle de sport, aux salles des associations étudiantes…

  Concernant la réservation des salles, 
certaines peuvent être réservées par tout le monde, d’autres requièrent l’autorisation d’un administrateur des salles 
(membre de l’établissement, membre du BDE, membre désigné des assos mettant à disposition leurs salles, etc.).

  Pour réserver une salle, 
il faudra indiquer dans un formulaire ; le nom d’une salle 
(liste déroulante, l’heure de début de créneau, l’heure de fin de créneau, les participants, le motif, etc.)

  Le site web se divisera en deux avec d’un côté, un partie client (frontend) accessible par tous, et une partie administrateur (backend) accessible uniquement pour certains utilisateurs.

<h3 > PRÉREQUIS </h3>

1. Mettre à jour le DATABASE_URL dans le fichier .env
2. Créer la base de données à l'aide de `php bin/console doctrine:create:database`
3. Charger la fixture pour créer l'utilisateur admin `php bin/console doctrine:fixtures:load --append`
4. Charger la base de donnée avec `php bin/console doctrine:migrations:migrate`
5. Installer composer préalablement telechargé avec `composer install`