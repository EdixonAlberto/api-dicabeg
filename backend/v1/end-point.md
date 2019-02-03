EndPoint
=

+ GET: **Obtener**

    **users/**
    + v1/users/accounts/
    + v1/users/id/accounts/
    + v1/users/data/
    + v1/users/id/data/
    + v1/users/id/referrals/
    + v1/users/id/history/

    **videos/**
    + v1/videos/
    + v1/videos/id/

    **providers/**
    + v1/providers/
    + v1/providers/id/
---

+ PUT: **Crear**

    **users/**
    + v1/users/accounts/
        + email
        + password
    + v1/users/id/referrals/
        + user_id del referido

    **videos/**
    + v1/users/id/history/
        + video_id
    +
---

+ PATCH: **Actualizar**
    + v1/users/id/accounts/
        + email or password
    + v1/users/id/data/
        + names
        + lastnames
        + age
        + image
        + phone
        + points
        + movile_data
---

+ DELETE: **Eliminar**
    + v1/users/id/
    + v1/users/id/referrals/
    + v1/users/id/referrals/id/
    + v1/users/id/history/
    + v1/users/id/history/id/
