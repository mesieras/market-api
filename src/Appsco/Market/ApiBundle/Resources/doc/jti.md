Market Notification JTI Validation
==================================

JWT ID validation is required to prevent forgery by replication in the time window when token has not yet expired.
Appsco Market API Bundle does not implement it in current version, since it would requires access to local
persistent storage, for example the database, and it might vary from case to case. So, to be fully secure you
should implement it yourself.

Here's how you can implement JTI validation on the Symfony 2 platform.

Entity
------

First you would need some persistent entity where processed JTI would be stored.

``` php
namespace AcmeBundle\Entity;

use Doctrine\Orm\Annotations as ORM;

/**
 * @ORM\Entity
 * @Table(name="my_jti_log")
 */
class JtiLog
{
    /**
     * @var string
     * @ORM\Id
     * @GeneratedValue(strategy="NONE")
     * @ORM\Column(type="string", length=60)
     */
    protected $jti;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;

    // getters & setters
}
```


Notification validator
----------------------

Now when you have an entity to store processed JWT IDs you can make the JTI notification validator

``` php
namespace AcmeBundle\Service\Market\Notification\Validator

use Appsco\Market\ApiBundle\Service\Notification\Validator\NotificationValidatorInterface;
use Appsco\Market\ApiBundle\Error\InvalidNotificationException;
use Appsco\Market\ApiBundle\Model\Notification;
use Doctrine\ORM\EntityManager;

class JtiValidator implements NotificationValidatorInterface
{
    /** @var EntityManager */
    protected $entityManager;

    /**
     * @param Notification $notification
     * @throws InvalidNotificationException
     * @return void
     */
    public function validate(Notification $notification)
    {
        $repository = $this->entityManager->getRepository('Acme:JtiLog');
        $existing = $repository->getBy(array('jti'=>$notification->getJwtId()));
        if ($existing) {
            throw new InvalidNotificationException(sprintf("Notification '%s' already processed", $notification->getJwtId()));
        }

        $jtiLog = new JtiLog();
        $jtiLog->setJti($$notification->getJwtId());
        $jtiLog->setCreatedAt(new \DateTime());
        $this->entityManager->persist($jtiLog);
        $this->entityManager->flush();
    }

}
```

Service registration
--------------------

Register your JTI validator as a service

``` yaml
# src/AcmeBundle/Resources/config/services.yml
services:
    acme.jti_validator:
        class: AcmeBundle\Service\Market\Notification\Validator\JtiValidator
        arguments: [@doctrine.orm.entity_manager]
```

Configuration
-------------

Now you have to tell the Market API Bundle to use your validator together with other builtin validators

``` yaml
# app/config/config.yml
appsco_market_api:
    # ...
    notification:
        appsco: true
        validators:
            - acme.jti_validator
```


