# Les événements

- [Utilisation basique](#basic-usage)
- [Ecouteur joker](#wildcard-listeners)
- [Utilisation de classes en tant qu'écouteur](#using-classes-as-listeners)
- [Mise en attente d'un événement](#queued-events)
- [Classes d'abonnement](#event-subscribers)

<a name="basic-usage"></a>
## Utilisation basique

La classe `Event` du framework Laravel vous permet de souscrire et d'écouter des événements dans votre application.

#### Enregistrement à un événement

    Event::listen('user.login', function($user)
    {
        $user->last_login = new DateTime;

        $user->save();
    });

#### Déclencher un événement

    $event = Event::fire('user.login', array($user));

Vous pouvez spécifier une priorité pour vos écouteurs d'événements. Les écouteurs ayant une plus grande priorité seront exécutés en premier, tandis que les écouteurs qui ont la même priorité seront exécutés dans leur ordre d'enregistrement.

#### Enregistrement à un événement avec priorité

    Event::listen('user.login', 'LoginHandler', 10);

    Event::listen('user.login', 'OtherHandler', 5);

Vous pouvez stopper la propagation d'un événement aux autres, en retournant 'false' depuis l'écouteur :

#### Stoppe la propagation d'un événement

    Event::listen('user.login', function($event)
    {
        // Handle the event...

        return false;
    });

<a name="where-to-register"></a>
### Où enregistrer ses événements

Vous savez comment enregistrer des événements, mais vous vous demandez maintenant où le faire. Ne vous inquietez pas, c'est une question très commune. Cependant, c'est une question difficile à répondre car vous pouvez le faire presque partout ! Mais, voici quelques astuces. Une fois n'est pas coutume, comme beaucoup de code d'amorçage, les fichiers `start` comme `app/start/global.php` sont de bons candidats.

Si vos fichiers `start` deviennent ingérables, vous pouvez créer un fichier `app/events.php` que vous inclurez dans vos fichiers `start`. C'est une solution simple qui garde vos enregistrement séparés du reste du code d'amorçage. Si préférez une approche basée sur une classe, vous pouvez enregistrer vos événemetns dans un [service provider](/4.1/ioc#service-providers). Aucune de ces approches n'est mieux qu'une autre, à vous de trouver celle qui vous semble le mieux basée sur la taille de votre application.

<a name="wildcard-listeners"></a>
## Ecouteurs joker

Lors de l'enregistrement d'un écouteur d'événement, vous pouvez utiliser un joker :

#### Enregistrement d'un écouteur joker

    Event::listen('foo.*', function($param)
    {
      // Handle the event...
    });

Cet écouteur va gérer tous les événement qui commencent par `foo.`.

Vous pouvez utiliser la méthode `Event::firing` pour déterminer quel événement a été lancé :

    Event::listen('foo.*', function($param)
    {
        if (Event::firing() == 'foo.bar')
        {
            //
        }
    });

<a name="using-classes-as-listeners"></a>
## Utilisation de classes en tant qu'écouteur

Dans certains cas, vous pourriez vouloir utiliser une classe pour gérer un événement plutôt qu'une fonction anonyme. Les événements de classes sont résolus grâce au [conteneur IoC de Laravel](/4.1/ioc), vous fournissant ainsi la puissance de l'injecteur de dépendance à votre classe.

#### Enregistrement d'une classe écouteur

    Event::listen('user.login', 'LoginHandler');

Par défaut, la méthode `handle` de la classe `LoginHandler` sera appelée :

#### Définition d'une classe écouteur d'événement

    class LoginHandler {

        public function handle($data)
        {
            //
        }

    }

Si vous ne souhaitez pas utiliser la méthode par défaut `handle`, vous pouvez préciser le nom d'une méthode que vous souhaitez utiliser :

#### Spécifie quelle méthode doit être utilisée

    Event::listen('user.login', 'LoginHandler@onLogin');

<a name="queued-events"></a>
## Événements en file d'attente

En utilisant les méthodes `queue` et `flush`, vous pouvez mettre en attente un événement à déclarer, mais sans le lancer tout de suite :

#### Enregistrement d'un événement dans la file d'attente

    Event::queue('foo', array($user));

#### Enregistrement d'un videur

    Event::flusher('foo', function($user)
    {
        //
    });

Finalement, vous pouvez exécuter le "videur" et vider tous les événements en attente avec la méthode `flush` :

  Event::flush('foo');

<a name="event-subscribers"></a>
## Classes d'abonnement

Les classes d'abonnement sont des classes qui peuvent souscrire à plusieurs événements, enregistrés au sein même de ces classes. Ces classes doivent définir une méthode `subscribe` qui reçoit en unique argument une instance du répartiteur d'événement :

#### Définition d'une classe d'abonnement

    class UserEventHandler {

        /**
         * Handle user login events.
         */
        public function onUserLogin($event)
        {
            //
        }

        /**
         * Handle user logout events.
         */
        public function onUserLogout($event)
        {
            //
        }

        /**
         * Register the listeners for the subscriber.
         *
         * @param  Illuminate\Events\Dispatcher  $events
         * @return array
         */
        public function subscribe($events)
        {
            $events->listen('user.login', 'UserEventHandler@onUserLogin');

            $events->listen('user.logout', 'UserEventHandler@onUserLogout');
        }

    }

Une fois que la classe a été définie, elle doit être enregistrée avec la classe `Event`.

#### Enregistrement d'une classe d'abonnement

    $subscriber = new UserEventHandler;

    Event::subscribe($subscriber);
