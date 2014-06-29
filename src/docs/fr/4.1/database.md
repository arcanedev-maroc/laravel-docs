# Interactions avec les bases de données

- [Configuration](#configuration)
- [Connexions de lecture / écriture](#read-write-connections)
- [Exécuter des requêtes](#running-queries)
- [Transaction sur la base de données](#database-transactions)
- [Accéder aux connexions](#accessing-connections)
- [Log de requête](#query-logging)

<a name="configuration"></a>
## Configuration

La connexion à une base de données et l'exécution d'une requête sont extrêmement simples à mettre en oeuvre avec Laravel. Configurer l'accès à la base de données s'effectue dans le fichier `app/config/database.php`. Les connexions et celle à utiliser par défaut peuvent être définies dans ce fichier. Le fichier contient des exemples pour tous les gestionnaires de base de données supportés.

Au début de l'année 2013, Laravel supporte MySQL, Postgres, SQLite et SQL Server.

<a name="read-write-connections"></a>
## Connexions de lecture / écriture

Parfois vous pourriez vouloir utiliser une base de données pour vos SELECT, et une autre pour vos INSERT, UPDATE, et DELETE. Laravel rend ça simple, et la bonne connexion sera utilisée que vous utilisez des requêtes brutes, le query builder, ou l'ORM Eloquent.

Pour voir comment les connexions sont configurées, regardons l'exemple suivant :

    'mysql' => array(
        'read' => array(
            'host' => '192.168.1.1',
        ),
       'write' => array(
           'host' => '196.168.1.2'
        ),
        'driver'    => 'mysql',
        'database'  => 'database',
        'username'  => 'root',
        'password'  => '',
        'charset'   => 'utf8',
        'collation' => 'utf8_unicode_ci',
         'prefix'    => '',
    ),

Notez que deux clés ont été ajoutées au tableau de configuration : `read` et `write`. Ces deux clés ont pour valeur un tableau avec une seule clé : `host`. Le reste des options sera fusionné depuis le tableau `mysql` principal. Donc, nous avons seulement besoin de placer les éléments que l'on souhaite surcharger dans les tableaux `read` et `write`. Donc, dans ce cas, `192.168.1.1` sera utilisé pour la lecture, tandis que `192.168.1.2` sera utilisé pour l'écriture. Les identifiants, le préfixe, l'encodage et toutes les autres options du tableau principal `mysql` seront partagées par ces deux connexions.

<a name="running-queries"></a>
## Exécuter des requêtes

Une fois la connexion configurée, vous pouvez exécuter des requêtes à l'aide de la classe `DB`.

#### Exécuter une commande Select

	$results = DB::select('select * from users where id = ?', array(1));

La méthode `select` retourne un tableau de lignes.

#### Exécuter une commande Insert

	DB::insert('insert into users (id, name) values (?, ?)', array(1, 'Dayle'));

#### Exécuter une commande Update

	DB::update('update users set votes = 100 where name = ?', array('John'));

#### Exécuter une commande Delete

	DB::delete('delete from users');

> **Remarque:** Les commandes `update` et `delete` retournent le nombre de lignes affectées par l'opération.

#### Exécuter une requête quelconque

	DB::statement('drop table users');

Vous pouvez écouter pour des événements de requêtes en utilisant la méthode `DB::listen` :

#### Ecoute d'événements de requêtes

    DB::listen(function($sql, $bindings, $time)
    {
        //
    });

<a name="database-transactions"></a>
## Transactions sur la base de données

Pour exécuter une liste d'opérations durant une transaction, vous pouvez utiliser la méthode `transaction` :

	DB::transaction(function()
	{
		DB::table('users')->update(array('votes' => 1));

		DB::table('posts')->delete();
	});

> **Note:** Si un exception est lancée dans la fonction anonyme de `transaction`, alors la transaction sera annulée automatiquement.

Vous pouvez démarrer une transaction vous même :

    DB::beginTransaction();

Vous pouvez ensuite annuler la transaction avec la méthode `rollback`:

    DB::rollback();

Et pour finir, vous pouvez commiter une transaction via la méthode `commit` :

    DB::commit();

<a name="accessing-connections"></a>
## Accéder aux connexions

Lorsque plusieurs connexions sont ouvertes, vous pouvez accéder à la connexion de votre choix à l'aide de la méthode `DB::connection` :

	$users = DB::connection('foo')->select(...);

Parfois vous pourriez avoir besoin de vous reconnecter à une base de données :

    DB::reconnect('foo');

Et si vous souhaitez vous déconnecter d'une base de données :

    DB::disconnect('foo');

<a name="query-logging"></a>
## Log de requête

Par défaut, Laravel tient un journal de log de toutes les requêtes qui ont été lancées pour la requête courante. Toutefois, dans certains cas, comme lors de l'insertion d'un grand nombre de lignes, cela peut entrainer une augmentation excessive de l'utilisation de la mémoire par l'application. Pour désactiver le journal de log, vous pouvez utiliser la méthode `disableQueryLog` :

    DB::connection()->disableQueryLog();

Pour récupérer les requêtes exécutées dans un tableau, vous pouvez utiliser la méthode `getQueryLog` :

    $queries = DB::getQueryLog();