End Point Api
=

>USERS

    GET    | api/users/page/{nro}/date-order/{order}
    GET    | api/users
    GET    | api/user/ranking

    POST   | api/users
    PACTH  | api/users
    DELETE | api/users

---

>ACCOUNTS

    POST   | api/accounts/login
    POST   | api/accounts/login/refresh
    POST   | api/accounts/activation
    POST   | api/accounts/recovery
    POST   | api/accounts/resend_email

    PUT    | api/update/email
    PUT    | api/update/password
---

>REFERREDS

    GET    | api/referreds/page/{nro}/date-order/{order}
    GET    | api/referreds/{id}

    DELETE | api/referreds/{id}

---

>TRANSFERS

    GET    | api/transfers/page/{nro}/date-order/{order}
    GET    | api/transfers/{code}

    POST   | api/transfers
    POST   | api/transfers/send_report

---

>HISTORY

    GET    | api/history/page/{nro}/date-order/{order}
    GET    | api/history/{video_id}

    POST   | api/history/{video_id}

    DELETE | api/history/{video_id}
    DELETE | api/history

---

>VIDEOS

    GET    | api/videos/page/{nro}/date-order/{order}
    GET    | api/videos/{id}

---

>ADVERTS

    GET    | api/adverts/grant/user-id/{userId}/rewards/{rewards}/event-id/{eventId}

---
