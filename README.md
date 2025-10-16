# BookShare - Plateforme d'Échange de Livres

<p align="center">
    <img src="https://via.placeholder.com/400x100/4F46E5/FFFFFF?text=BookShare" alt="BookShare Logo">
</p>

<p align="center">
Une plateforme moderne d'échange de livres avec intelligence artificielle intégrée, développée avec Laravel.
</p>

## 📖 À Propos du Projet

BookShare est une application web innovante qui permet aux utilisateurs d'échanger des livres de manière intelligente et sécurisée. La plateforme intègre des fonctionnalités d'intelligence artificielle pour améliorer l'expérience utilisateur et faciliter la modération.

## ✨ Fonctionnalités Principales

### 📚 Gestion des Livres
- **Catalogue complet** : Ajout, modification et recherche de livres
- **Catégorisation intelligente** : Organisation par genres et catégories
- **Images et descriptions** : Support multimédia pour chaque livre
- **Système de notation** : Évaluations et commentaires des utilisateurs

### 🔄 Système d'Échange
- **Propositions d'échange** : Interface intuitive pour proposer des échanges
- **Suivi des transactions** : Historique complet des échanges
- **Notifications en temps réel** : Alertes pour les nouvelles propositions
- **Statuts dynamiques** : Suivi de l'état des échanges (en attente, accepté, refusé, terminé)

### 🤖 Intelligence Artificielle Intégrée
- **Classification automatique des signalements** : IA pour catégoriser les rapports utilisateurs
- **Recommandations personnalisées** : Suggestions de livres basées sur les préférences
- **Modération intelligente** : Assistance IA pour la gestion des contenus

### 👥 Gestion des Utilisateurs
- **Authentification sécurisée** : Système de connexion robuste
- **Profils personnalisables** : Gestion des informations personnelles
- **Système de réputation** : Évaluations entre utilisateurs
- **Interface d'administration** : Panneau de contrôle pour les administrateurs

### 🛡️ Sécurité et Modération
- **Système de signalement** : Rapports d'incidents avec classification IA
- **Notifications intelligentes** : Alertes automatiques pour les administrateurs
- **Politiques de sécurité** : Protection des données utilisateurs
- **Audit trail** : Traçabilité des actions importantes

## 🔧 Technologies Utilisées

### Backend
- **Laravel 11** - Framework PHP moderne
- **MySQL** - Base de données relationnelle
- **Eloquent ORM** - Gestion des données
- **Laravel Breeze** - Authentification

### Frontend
- **Blade Templates** - Moteur de templates Laravel
- **Tailwind CSS** - Framework CSS utilitaire
- **Alpine.js** - Framework JavaScript léger
- **Vite** - Build tool moderne

### Intelligence Artificielle
- **Hugging Face API** - Services d'IA avancés
- **Modèles de classification** - Analyse automatique des contenus
- **Système de recommandations** - Algorithmes de suggestion

## 📋 Prérequis

- PHP 8.2 ou supérieur
- Composer
- Node.js et npm
- MySQL 8.0 ou supérieur
- Extension PHP mbstring, openssl, pdo, tokenizer, xml

## 🚀 Installation

### 1. Cloner le repository
```bash
git clone https://github.com/votre-username/bookshare.git
cd bookshare
```

### 2. Installer les dépendances PHP
```bash
composer install
```

### 3. Installer les dépendances JavaScript
```bash
npm install
```

### 4. Configuration de l'environnement
```bash
cp .env.example .env
php artisan key:generate
```

### 5. Configuration de la base de données
Éditez le fichier `.env` avec vos paramètres de base de données :
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bookshare
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 6. Configuration de l'API Hugging Face
Ajoutez votre clé API Hugging Face dans le fichier `.env` :
```env
HUGGINGFACE_API_KEY=your_huggingface_api_key
```

### 7. Exécuter les migrations
```bash
php artisan migrate
```

### 8. Peupler la base de données (optionnel)
```bash
php artisan db:seed
```

### 9. Créer le lien symbolique pour le stockage
```bash
php artisan storage:link
```

### 10. Compiler les assets
```bash
npm run build
```

## 🔄 Développement

### Démarrer le serveur de développement
```bash
php artisan serve
```

### Compiler les assets en mode développement
```bash
npm run dev
```

### Regarder les changements (hot reload)
```bash
npm run dev -- --watch
```

## 📊 Structure de la Base de Données

### Tables Principales
- **users** : Informations des utilisateurs
- **books** : Catalogue des livres
- **categories** : Classification des livres
- **exchanges** : Transactions d'échange
- **reports** : Signalements et modération
- **notifications** : Système d'alertes
- **reviews** : Évaluations et commentaires

## 🤖 Fonctionnalités IA

### Classification des Signalements
- **Endpoint** : `/api/classify-report`
- **Modèle** : Classification automatique des types de problèmes
- **Catégories** : Spam, contenu inapproprié, faux profils, etc.

### Recommandations de Livres
- **Endpoint** : `/api/recommend-books`
- **Algorithme** : Analyse des préférences utilisateur
- **Personnalisation** : Suggestions basées sur l'historique

## 👨‍💼 Administration

### Accès Administrateur
1. Créer un compte utilisateur normal
2. Modifier le champ `is_admin` à `1` dans la base de données
3. Accéder au panneau d'administration via `/admin`

### Fonctionnalités Administrateur
- Gestion des utilisateurs
- Modération des signalements
- Supervision des échanges
- Statistiques de la plateforme

## 🧪 Tests

### Exécuter les tests
```bash
php artisan test
```

### Tests avec couverture
```bash
php artisan test --coverage
```

## 📝 API Documentation

### Endpoints Principaux
- `GET /api/books` - Liste des livres
- `POST /api/exchanges` - Créer un échange
- `POST /api/classify-report` - Classification IA
- `POST /api/recommend-books` - Recommandations

## 🛠️ Maintenance

### Optimiser l'application
```bash
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Nettoyer le cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

## 📈 Monitoring

### Logs
Les logs sont stockés dans `storage/logs/laravel.log`

### Performance
- Utilisation d'Eloquent avec eager loading
- Cache des requêtes fréquentes
- Optimisation des images

## 🔒 Sécurité

- Protection CSRF activée
- Validation des entrées utilisateur
- Hashage sécurisé des mots de passe
- Protection contre les injections SQL
- Rate limiting sur les API

## 🤝 Contribution

1. Fork le projet
2. Créer une branche feature (`git checkout -b feature/AmazingFeature`)
3. Commit les changements (`git commit -m 'Add some AmazingFeature'`)
4. Push vers la branche (`git push origin feature/AmazingFeature`)
5. Ouvrir une Pull Request

## 📄 Licence

Ce projet est sous licence MIT. Voir le fichier `LICENSE` pour plus de détails.

## 📞 Support

Pour toute question ou support :
- Email : support@bookshare.com
- Documentation : [Wiki du projet](https://github.com/votre-username/bookshare/wiki)
- Issues : [GitHub Issues](https://github.com/votre-username/bookshare/issues)

## 🙏 Remerciements

- **Laravel Team** pour le framework exceptionnel
- **Hugging Face** pour les modèles d'IA
- **Tailwind CSS** pour le design system
- **Communauté Open Source** pour les contributions

---

<p align="center">
Développé avec ❤️ pour la communauté des passionnés de lecture
</p>
