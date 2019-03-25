EndPoint
=

>USERS

    GET    | v?/users
    GET    | v?/users/group/nro    [NEW]
    GET    | v?/users/id
    POST   | v?/users
    PACTH  | v?/users/id
    DELETE | v?/users/id
---

>REFERRALS

    GET    | v?/users/id/referrals/
    GET    | v?/users/id/referrals/group/nro    [NEW]
    GET    | v?/users/id/referrals/id/
    DELETE | v?/users/id/referrals/id/

---

>SESSIONS

    GET    | v?/sessions/
    POST   | v?/sessions/
    PACTH  | v?/sessions/
    DELETE | v?/sessions/

---

>HISTORY

    GET    | v?/users/id/history/
    GET    | v?/users/id/history/group/nro  [NEW]
    GET    | v?/users/id/history/id/
    POST   | v?/users/id/history/id/
    DELETE | v?/users/id/history/   [NEW]
    DELETE | v?/users/id/history/id/
---

>VIDEOS

    GET    | v?/videos/
    GET    | v?/videos/group/nro    [NEW]
    GET    | v?/videos/id/

---

>OPTIONS

    GET    | v?/options/time/
    POST   | v?/options/time/
