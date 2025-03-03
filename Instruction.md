# SAE Maintenance - Système de Connexion avec Profil

## Description
Cette partie du projet ajoute un système de connexion avec profils d'utilisateur à l'application. Il permettra aux utilisateurs de s'inscrire mais aussi de connecter et suivre leurs statistiques d'exercices réalisés.

## Fonctionnalités
- **Inscription des utilisateurs** 
- **Connexion pour la gestion de session**.
- **Enregistrement des exercices** 
- **Affichage des statistiques**
- **Déconnexion**.

## Installation
### Prérequis
- Une base de données **MySQL**.

### Configuration
1. **Créer la base de données** dans MySQL :
```sql
CREATE DATABASE sae_maintenance;
USE sae_maintenance;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE exercises (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    exercise_name VARCHAR(100) NOT NULL,
    score INT DEFAULT 0,
    completed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

2. **Ensuite modifier `config.php`** avec vos informations de connexion MySQL :
```php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sae_maintenance";
```

3. **Lancer le projet** en ouvrant un serveur local (ex: Mamp, WAMP) et accéder aux fichiers via le navigateur.

## Utilisation
1. **S'inscrire** via le fichier `enregister.php`.
2. **Se connecter** via le fichier `connexion.php`.
3. **Accéder à son profil** via le fichier `profil.php`.
4. **Se déconnecter** via le fichier `deconnexion.php`.


**Auteur :** Alexis Payen
**Licence :** Libre 

