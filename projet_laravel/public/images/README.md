# Instructions pour ajouter l'image du hero

## Étape 1: Télécharger l'image
Téléchargez l'image du vieux monsieur qui lit un livre (celle que vous avez fournie).

## Étape 2: Placer l'image
Placez l'image dans ce dossier avec le nom exact suivant:
- **Nom du fichier**: `reading-old-man.jpg`
- **Chemin complet**: `public/images/reading-old-man.jpg`

## Étape 3: Vérifier
Une fois l'image placée, rafraîchissez votre page dashboard et l'image devrait s'afficher automatiquement.

## Note
Si votre image est au format PNG, renommez-la en `.jpg` ou modifiez le code dans `user-dashboard.blade.php` ligne 91:
```php
<img src="{{ asset('images/reading-old-man.png') }}" 
```

## Alternative
Si vous préférez utiliser un autre nom de fichier, modifiez simplement la ligne 91 dans le fichier:
`resources/views/pages/user-dashboard.blade.php`
