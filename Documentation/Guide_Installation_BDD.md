# Documentation pour l'Aide à l'Installation de la Base de Données MySQL avec PHPMyAdmin

## 1. Installation de XAMPP ou WAMP

Bienvenue dans le guide d'installation de la base de données du site d'aide maternel. Avant de configurer la base de données, vous devez avoir installé un serveur local comme XAMPP ou WAMP, qui inclut PHP, MySQL, et PHPMyAdmin.

- **Téléchargez et installez XAMPP** à partir de [https://www.apachefriends.org/fr/index.html](https://www.apachefriends.org/fr/index.html).
- **Téléchargez et installez WAMP** à partir de [https://www.wampserver.com/](https://www.wampserver.com/).

## 2. Lancer le Serveur MySQL et PHPMyAdmin

1. Ouvrez XAMPP ou WAMP et lancez **Apache** et **MySQL**.
2. Accédez à **PHPMyAdmin** en ouvrant votre navigateur et en allant à l'adresse suivante : 
   - `http://localhost/phpmyadmin`

## 3. Créer la Base de Données

1. Ouvrez PHPMyAdmin.
2. Créez une nouvelle base de données :
   - Cliquez sur **Nouvelle** dans le menu de gauche.
   ![Tuto](image.png)
   - Entrez un nom pour la base de données, par exemple **exercice_app **comme moi.
   - Cliquez sur **Créer**.

### 4. Créer les Tables

Une fois la base de données créée, vous devez créer les tables nécessaires. Utilisez les requêtes SQL ci-dessous dans l'onglet **SQL** de PHPMyAdmin.

#### Insérer les Tables `users` et `exercices`

```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(200) NOT NULL,
    email VARCHAR(188) NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    role ENUM('enfant', 'parent', 'enseignant') NOT NULL,
    child_name VARCHAR(100) NULL,
    teacher_students TEXT NULL,
    teacher_id INT NULL
);

CREATE TABLE exercices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    exercice_name VARCHAR(255) NOT NULL,
    score INT NOT NULL,
    date_completed DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE INDEX idx_user_id ON exercices(user_id);
```
### 5. Créer la liaison PHP/BDD

Enfin, il ne vous reste plus qu'à créer votre fichier config.php pour relier vos fichiers PHP à la base de données. Voici mon exemple :

```php
<?php
// Paramètres de connexion à la base de données
$host = 'localhost:3308'; // Le nom d'hôte (localhost) et le port (3308). Si votre MySQL fonctionne sur le port par défaut (3306), vous pouvez simplement écrire 'localhost'.
$dbname = 'exercice_app'; // Le nom de la base de données à laquelle vous voulez vous connecter
$username = 'root'; // Le nom d'utilisateur pour accéder à la base de données (ici 'root' pour un serveur local)
$password = ''; // Le mot de passe pour l'utilisateur MySQL. S'il n'y en a pas, laissez la chaîne vide.

// Tentative de connexion à la base de données avec PDO
try {
    // Création de l'instance PDO pour se connecter à la base de données
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

    // Définir le mode d'erreur de PDO pour qu'il lance une exception en cas d'erreur
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    // Si la connexion échoue, une exception est lancée et l'erreur est affichée
    die("Erreur de connexion : " . $e->getMessage());
}
?>
```