# Test Dialotel

## Démarrer serveur en local
```bash
$ symfony serve -d
```
Lien pour accéder à l'interface: http://127.0.0.1:8000

## Lignes jouées:
### Création projet
```bash
$ symfony new --full dialotel
```

### Création bdd, entité Cartes, jouers les migrations
```bash
$ symfony console doctrine:database:create  
```