EndPoint
=

>USERS

    GET    | v2/users/page/{nro}
    GET    | v2/users
    POST   | v2/users
    PACTH  | v2/users
    DELETE | v2/users

---

>ACCOUNTS

    POST   | v2/accounts/activation
    POST   | v2/accounts/login
    POST   | v2/accounts/login/refresh
    POST   | v2/accounts/recovery
    POST   | v2/accounts/send_email

---

>TRANSFERS

    GET    | v2/transfers/page/{nro}
    GET    | v2/transfers/{code}
    POST   | v2/transfers

---

>REFERREDS

    GET    | v2/referreds/page/{nro}
    GET    | v2/referreds/{id}
    DELETE | v2/referreds/{id}

---

>HISTORY

    GET    | v2/history/page/{nro}
    GET    | v2/history/{video_id}
    POST   | v2/history/{video_id}
    DELETE | v2/history/{video_id}
    DELETE | v2/history

---

>VIDEOS

    GET    | v2/videos/page/{nro}
    GET    | v2/videos/{id}

---
