# MossHeaven
- MossHeaven -

MossHeaven est un fake e-commerce pour les amoureux des Bryophytes.

Il est construit avec le framework Symfony 6 en server-side rendering, avec Twig comme moteur de template et Doctrine comme ORM.

La partie UI est aménagée avec Bootstrap et un peu de CSS.
Le back-office est géré avec le bundle EasyAdmin 4.
Les "paiements" sont gérés via l'API Stripe, qui donne accès à une clé de test.
L'envoi de mails est géré avec l'API MailJet.

Le Javascript est réduit au strict minimum (issu de Bootstrap, des bundles et des API) pour mettre en avant les limitations du server-side rendering.

