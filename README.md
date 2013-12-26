SecureApiBundle
===============

Stateless authentication for APIs based on Symfony 2.

# Installation

```composer require darsadow/secure-api-bundle```

or

```
    // ...
    "darsadow/secure-api-bundle": "dev-master"
    // ...
```

Add bundle to your AppKernel.php

```php
// ...
    new Darsadow\Bundle\SecureApiBundle\DarsadowSecureApiBundle(),
// ...
```

# Usage

1. Create user provider implementing ApiUserInterface
2. Configure your security layer:

```yaml
security:
    encoders:
       Darsadow\Bundle\UserBundle\Entity\User: sha512

    role_hierarchy:
        ROLE_ADMIN:       ROLE_USER
        ROLE_SUPER_ADMIN: [ROLE_USER, ROLE_ADMIN, ROLE_ALLOWED_TO_SWITCH]

    providers:
        secure_api_provider:
            id: darsadow.user.repository.user_repository

    firewalls:
        dev:
            pattern:  ^/(_(profiler|wdt)|css|images|js)/
            security: false

        register:
            pattern:  ^/api/register$
            security: false
        session:
            pattern:  ^/api/session$
            security: false

        api:
            pattern: ^/api/.*
            stateless: true
            secure-api: true
            provider: secure_api_provider
```

# TODO

* Tests
* Better readme file ;)
